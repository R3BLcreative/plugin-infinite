<?php

/**
 * Class that extends custom functionality to the plugin
 *
 * A class definition that allows for drop-in extension of the plugin that enables 
 * additional customizations and integrations that taylor to the clients needs and 
 * overall project scope.
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    Infinite
 * @subpackage Infinite/extensions
 */

require_once plugin_dir_path(__DIR__) . 'vendor/autoload.php';

class INF_Stripe {
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	public $last_error;

	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	private $stripe;

	/**
	 * Undocumented function
	 */
	public function __construct() {
		$sk = (get_field('stripe_mode', 'option') == 'live') ? get_field('sk_live', 'option') : get_field('sk_test', 'option');

		$this->stripe = new \Stripe\StripeClient($sk);
	}

	/**
	 * Undocumented function
	 *
	 * @param  [type] $confirm
	 * @param  [type] $form
	 * @param  [type] $entry
	 * @param  [type] $ajax
	 * @return void
	 */
	public function redirect_to_stripe_checkout($confirm, $form, $entry, $ajax) {
		// Get form confirmation URL
		$success = rgar($confirm, 'redirect');

		// Init session array
		$session = [
			'client_reference_id' => $_SESSION['order_id'],
			'success_url' => $success,
			'cancel_url' => get_home_url() . '/cart',
			'mode' => 'payment',
			'customer_email' => $entry[4],
		];

		// Get cart session and create array of line items
		$line_items = [];
		foreach ($_SESSION['cart']['items'] as $item) {
			if ($item['isRegistered']) continue;

			// Get image
			$image = get_field('featured_image', $item['id']);
			$price = get_field('course_price', $item['id']) * 100;
			$name = get_the_title($item['id']) . ' (' . get_field('course_code', $item['id']) . ')';

			//
			$line_items[] = [
				// 'price' => get_field('price_id', $item['id']),
				'price_data' => [
					'currency' => 'USD',
					'tax_behavior' => 'inclusive',
					'unit_amount' => $price,
					'product_data' => [
						'name' => $name,
						'description' => $item['sched'],
						'images' => [$image['url']],
					],
				],
				'quantity' => 1,
			];
		}
		$session['line_items'] = $line_items;

		$response = $this->stripe->checkout->sessions->create($session);
		if (isset($response->url)) $confirm['redirect'] = $response->url;

		return $confirm;
	}

	//----------------- WEBHOOK ENDPOINTS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function stripe_wh_callback() {
		$endpoint_secret = get_field('wh_secret', 'option');
		$payload = @file_get_contents('php://input');
		$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
		$event = null;

		try {
			$event = \Stripe\Webhook::constructEvent(
				$payload,
				$sig_header,
				$endpoint_secret
			);
		} catch (\UnexpectedValueException $e) {
			// Invalid payload
			http_response_code(400);
			exit();
		} catch (\Stripe\Exception\SignatureVerificationException $e) {
			// Invalid signature
			http_response_code(400);
			exit();
		}

		// Handle the event
		switch ($event->type) {
			case 'checkout.session.completed':
				$OID = $event->data->object->client_reference_id;

				$ROSTER = new INF_Rosters;
				$ROSTER->update($OID);
				break;
			default:
				// Received other event type
				break;
		}

		http_response_code(200);
	}
}

// Creates webhook endpoint for updating DB after stripe payments
add_action('rest_api_init', function () {
	register_rest_route('bvwp/v1', '/stripe', [
		'methods' => 'POST',
		'callback' => [new INF_Stripe, 'stripe_wh_callback'],
		'permission_callback' => function () {
			return true;
		}
	]);
});
