<?php

	$sample_data = '{ "qqq": [ { "a": "Conne", "b": "1", "c": "2014-05-19T15:40:06+05:30", "d": { "d1": "dani", "d6": "admin" } }, { "a": "test1", "b": "1235", "c": "2014-    05-27T11:23:11+05:30", "d": { "d1": "sev", "d6": "eev" } }, { "a": "test2", "b": "1235", "c": "2014-05-27T11:23:11+05:30", "d": { "d1": "sev", "d6": "eev" } }, { "a": "test3", "b":     "1235", "c": "2014-05-27T11:23:11+05:30", "d": { "d1": "sev", "d6": "eev" } }, { "a": "test4", "b": "1235", "c": "2014-05-27T11:23:11+05:30", "d": { "d1": "sev", "d6": "eev" } }, { "a":     "test5", "b": "1235", "c": "2014-05-27T11:23:11+05:30", "d": { "d1": "sev", "d6": "eev" } }, { "a": "test6", "b": "1235", "c": "2014-05-27T11:23:11+05:30", "d": { "d1": "sev", "d6": "eev"     } }, { "a": "test7", "b": "1235", "c": "2014-05-27T11:23:11+05:30", "d": { "d1": "sev", "d6": "eev" } }, { "a": "test8", "b": "1235", "c": "2014-05-27T11:23:11+05:30", "d": { "d1": "sev",     "d6": "eev" } }, { "a": "test9", "b": "1235", "c": "2014-05-27T11:23:11+05:30", "d": { "d1": "sev", "d6": "eev" } }, { "a": "test10", "b": "1235", "c": "2014-05-27T11:23:11+05:30", "d": {     "d1": "sev", "d6": "eev" } }, { "a": "test11", "b": "1235", "c": "2014-05-27T11:23:11+05:30", "d": { "d1": "sev", "d6": "eev" } } ]}';

	// just normal getting data
	$raw_data = json_decode($sample_data, true);
	$raw_data = $raw_data['qqq'];

	// use get variable to paging number
	$page = !isset($_GET['page']) ? 1 : $_GET['page'];
	$limit = 5; // five rows per page
	$offset = ($page - 1) * $limit; // offset
	$total_items = count($raw_data); // total items
	$total_pages = ceil($total_items / $limit);
	$final = array_splice($raw_data, $offset, $limit); // splice them according to offset and limit
	$self = $_SERVER['PHP_SELF'];

	?>
	<!-- print links -->
	<?php for($x = 1; $x <= $total_pages; $x++): ?>
		<a href='<?=$self?>?page=<?php echo $x; ?>'><?php echo $x; ?></a>
	<?php endfor; ?>
	<table border="1" cellpadding="10">
		<tr><th>Column 1</th><th>Column 2</th><th>Time</th><th>Column 4</th></tr>
		<?php foreach($final as $key => $value): ?>
			<tr>
			<?php foreach($value as $index => $element): ?>
				<td><?php echo !is_array($element) ? $element : implode(',', $element); ?></td>
			<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</table>