<?php 

function delete_strings($line, $del_str_list) {
	// $lineをCSVとして分割し、各要素について
	// $del_str_list に含まれる文字列が部分一致で含まれていれば削除する。
	// 残った要素はカンマで結合して返す。
	$fields = str_getcsv($line);
	$result = array();
	foreach ($fields as $field) {
		$val = trim($field);
		if ($val === '') continue;
		$remove = false;
		foreach ($del_str_list as $del) {
			if ($del === '') continue;
			// マルチバイト対応の部分文字列検索
			if (mb_strpos($val, $del) !== false) {
				$remove = true;
				break;
			}
		}
		if (!$remove) $result[] = $val;
	}
	return implode(',', $result);

}
$del_str_list = array("ribbon", "hairclip", "shiny", "bangs", "twintails");
$idx = 0;
$first = 0;
$fname=$argv[1];
while (($line = fgets(STDIN)) !== false) {
	$line = chop($line);
	if (strpos($line, "comic,") !== false) {
		continue;
	}
	$line = delete_strings($line, $del_str_list);
	if ($idx == 0) {
		print "<?php \n";
	}
	$str = '$base_scn_array[] = ' . sprintf("\"prefix=%03d_%s%%%%%%%s\";\n", $idx, $fname, $line);
	print $str;
	$idx++;
}
