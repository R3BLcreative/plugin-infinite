<?php
extract($args);

// Data needed... RID, CID, SID
$CID = $data['course_id'];
$selected = $data['schedule'];
// $schedules = get_field('course_dates', $CID);
$scheds = get_field('course_dates', $CID);
$filtered = array_filter($scheds, function ($v, $k) {
	$now = strtotime('now');
	$start = strtotime($v['start_date']);
	$end = (!empty($v['end_date'])) ? strtotime($v['end_date']) : $now;
	return $now < $start || $now < $end;
}, ARRAY_FILTER_USE_BOTH);
?>

<div>
	<input type="hidden" name="RID" value="<?php echo $data['RID']; ?>" />
	<label for="suffix" class="font-semibold text-secondary">Move to Roster</label>
	<select id="roster" name="updates[schedule]" class="w-full">
		<option value="" disabled>Select One</option>
		<?php
		foreach ($filtered as $sched) :
			// Format
			$value = $sched['start_date'];
			$value .= (!empty($sched['end_date'])) ? ' - ' . $sched['end_date'] : '';

			// Selected
			$isSelected = (isset($selected) && $selected == $value) ? 'selected' : '';
		?>
			<option value="<?php echo $value; ?>" <?php echo $isSelected; ?>>
				<?php echo $value; ?>
			</option>
		<?php endforeach; ?>
	</select>
</div>