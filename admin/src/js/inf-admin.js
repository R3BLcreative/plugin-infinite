/* ------------------------------------
INLINE FORMS FUNCTIONALITY
------------------------------------ */

const inlineBtns = document.querySelectorAll('.edit-inline-btn');

if (inlineBtns.length > 0) {
	inlineBtns.forEach((btn) => {
		const slug = btn.dataset.slug;
		const inline_id = slug + '-form';
		const display_id = slug + '-display';
		const inline = document.getElementById(inline_id);
		const display = document.getElementById(display_id);

		btn.onclick = function () {
			const expanded = btn.getAttribute('aria-expanded');
			if (expanded === 'false') {
				btn.setAttribute('aria-expanded', 'true');
				inline.classList.remove('hidden');
				inline.classList.add('block');
				display.classList.remove('block');
				display.classList.add('hidden');
			} else {
				btn.setAttribute('aria-expanded', 'false');
				inline.classList.remove('block');
				inline.classList.add('hidden');
				display.classList.remove('hidden');
				display.classList.add('block');
			}
		};
	});
}

/* ------------------------------------
MODAL FORMS FUNCTIONALITY
------------------------------------ */

const modalBtns = document.querySelectorAll('.edit-modal-btn');

if (modalBtns.length > 0) {
	modalBtns.forEach((btn) => {
		const slug = btn.dataset.slug;
		const modal_id = slug + '-form';

		const modal = document.getElementById(modal_id);
		const close_id = modal_id + '-close';
		const close = document.getElementById(close_id);

		btn.onclick = function () {
			modal.style.display = 'block';
		};

		close.onclick = function () {
			modal.style.display = 'none';
		};

		window.onclick = function (event) {
			if (event.target == modal) {
				modal.style.display = 'none';
			}
		};
	});
}

/* ------------------------------------
TOGGLE SWITCHES
------------------------------------ */

const toggleSwitch = (toggle) => {
	const toggleChecked = toggle.getAttribute('aria-checked') === 'true';
	const radioUnchecked = toggle.querySelector('#value-unchecked');
	const radioChecked = toggle.querySelector('#value-checked');

	radioUnchecked.checked = toggleChecked;
	radioChecked.checked = !toggleChecked;
	toggle.setAttribute('aria-checked', !toggleChecked);
};

/* -------------------------------
TABLE VIEW GET FILTER BY VALUES
------------------------------- */
const getFilterValues = async (el) => {
	const row = el.parentNode.parentNode;
	const filterValsSelect = row.querySelector('select[name="filterval"]');
	const filterby = el.value;
	const screen = el.dataset.screen;
	const action = screen + '_getFilterValues';

	// Get localized ajaxurl
	const url = aap_ajax_obj.ajaxurl + '&action=' + action + '&filterby=' + filterby;

	const response = await fetch(url);
	const data = await response.json();

	// Prep for element creation
	const options = document.createDocumentFragment();

	// Create placeholder option
	let placeholder = document.createElement('option');
	placeholder.disabled = true;
	placeholder.value = 0;
	placeholder.selected = true;
	placeholder.innerHTML = '---';
	options.appendChild(placeholder);

	// Create options
	data.map((val) => {
		let option = document.createElement('option');
		option.value = val;
		option.innerHTML = val;
		options.appendChild(option);
	});

	filterValsSelect.replaceChildren(options);
};

/* -------------------------------
COUNT SELECTED LIST VIEW ROWS 
------------------------------- */

const countListViewSelected = () => {
	const selectors = document.querySelectorAll(
		'input[type="checkbox"]:checked.list-view-row-selectors'
	);
	const count = selectors.length;
	const counter = document.querySelector('#list-view-selected-counter');
	counter.innerHTML = count;
};

/* -------------------------------
LIST VIEW ACTIONS HANDLER 
------------------------------- */

const handleListViewAction = (el) => {
	const recordID = el.dataset.record;
	const action = el.dataset.action;
	const verify = el.dataset.verify;
	const url = aap_ajax_obj.ajaxurl + '&action=' + action + '&ID=' + recordID;

	if (verify) {
		buildModalINF({
			showModalTitle: true,
			modalTitle: 'Confirm Action',
			showModalActions: true,
			modalContent: 'Are you sure you want to do this?',
			confirmed: url,
		});
	}
};

/* -------------------------------
MODAL ACTIONS
------------------------------- */

const modalActionsClickHandler = async ({ currentTarget }) => {
	const action = currentTarget.dataset.action;
	let modal = null;

	switch (action) {
		case 'backdrop':
			if (currentTarget.parentNode) currentTarget.parentNode.removeChild(currentTarget);
			break;

		case 'confirm':
			const url = currentTarget.dataset.url;
			console.log(url);

			// Fetch
			modal = currentTarget.parentNode.parentNode.parentNode;
			if (modal.parentNode) modal.parentNode.removeChild(modal);

			const response = await fetch(url);
			location.reload();
			break;

		// Handle close after confirm
		case 'close':
		case 'cancel':
			modal = currentTarget.parentNode.parentNode.parentNode;
			if (modal.parentNode) modal.parentNode.removeChild(modal);
			break;

		default:
			// Do nothing by default
			break;
	}
};

/* -------------------------------
MODAL BUILDER
------------------------------- */
const buildModalINF = async ({
	showModalTitle = false,
	showModalActions = false,
	modalTitle = null,
	modalContent = null,
	confirmed = null,
} = {}) => {
	// Get icons
	const response = await fetch(
		aap_ajax_obj.ajaxurl + '&action=aap_get_icons&icons[]=close&icons[]=cancel&icons[]=check'
	);
	const data = await response.json();
	const icons = data.data;

	// Backdrop
	var modal = document.createElement('div');
	modal.setAttribute('id', 'modal-backdrop');
	modal.setAttribute('data-action', 'backdrop');
	modal.addEventListener('click', modalActionsClickHandler);
	modal.classList.add(
		'fixed',
		'top-0',
		'left-0',
		'w-full',
		'h-full',
		'z-[9999]',
		'bg-surface-500/50',
		'flex',
		'items-start',
		'justify-center',
		'px-6',
		'pt-[150px]',
		'pb-6',
		'backdrop-blur-sm'
	);

	// Wrapper
	var wrapper = document.createElement('div');
	wrapper.setAttribute('id', 'modal-wrapper');
	wrapper.classList.add(
		'w-1/4',
		'p-6',
		'bg-surface-800',
		'rounded-lg',
		'border',
		'border-surface-500',
		'shadow-inner',
		'text-white',
		'flex',
		'flex-col',
		'items-stretch',
		'justify-stretch',
		'gap-6'
	);

	// Title Bar
	var titleBar = document.createElement('div');
	titleBar.setAttribute('id', 'modal-title-bar');
	titleBar.classList.add('flex', 'items-center', 'justify-between', 'gap-6');

	// Title
	var title = document.createElement('h1');
	title.setAttribute('id', 'modal-title');
	title.classList.add('font-semibold', 'text-white', 'text-lg', 'tracking-wider');
	title.innerHTML = modalTitle;
	if (!showModalTitle) title.classList.add('invisible');
	titleBar.appendChild(title);

	// Close
	var close = document.createElement('button');
	close.setAttribute('type', 'button');
	close.setAttribute('id', 'modal-close');
	close.setAttribute('data-action', 'close');
	close.addEventListener('click', modalActionsClickHandler);
	close.classList.add('fill-primary', 'w-6', 'hover:fill-secondary');
	close.innerHTML = '<i class="w-6">' + icons.close + '</i>';
	titleBar.appendChild(close);

	// Add titleBar to wrapper
	wrapper.appendChild(titleBar);

	// Content
	var content = document.createElement('div');
	content.setAttribute('id', 'modal-content');
	content.classList.add('leading-relaxed', 'text-center');
	content.innerHTML = modalContent;
	wrapper.appendChild(content);

	if (showModalActions) {
		// Actions
		var actions = document.createElement('div');
		actions.setAttribute('id', 'modal-actions');
		actions.classList.add('flex', 'items-center', 'justify-center', 'gap-10', 'mt-6');

		// Cancel
		var cancel = document.createElement('button');
		cancel.setAttribute('type', 'button');
		cancel.setAttribute('id', 'modal-cancel');
		cancel.setAttribute('data-action', 'cancel');
		cancel.classList.add('aap-button', 'btn-alt');
		cancel.innerHTML = '<i class="w-6">' + icons.cancel + '</i>Cancel';
		cancel.addEventListener('click', modalActionsClickHandler);
		actions.appendChild(cancel);

		// Confirm
		var confirm = document.createElement('button');
		confirm.setAttribute('type', 'button');
		confirm.setAttribute('id', 'modal-confirm');
		confirm.setAttribute('data-action', 'confirm');
		confirm.setAttribute('data-url', confirmed);
		confirm.classList.add('aap-button', 'btn-primary');
		confirm.innerHTML = '<i class="w-6">' + icons.check + '</i>Confirm';
		confirm.addEventListener('click', modalActionsClickHandler);
		actions.appendChild(confirm);

		// Add actions to wrapper
		wrapper.appendChild(actions);
	}

	// Add wrapper to modal
	modal.appendChild(wrapper);

	// Add modal to screen
	const aap = document.querySelector('#aap-content');
	aap.appendChild(modal);
};
