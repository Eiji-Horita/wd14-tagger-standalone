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
for ($i = 1 ; $i < $argc ; $i++) {
	$fname=$argv[$i];
	// $fnameのbasenameを取得して、拡張子を除いたものを$fnameにする。
	$orig_filename = $fname;
	$fname = pathinfo($fname, PATHINFO_FILENAME);
	// $original_filenameの名前でファイルを開いて、その内容を1行ずつ読み込む。
	$handle = fopen($orig_filename, "r");
	if ($handle) {
		$ext = fgets($handle);
		// $orig_filenameの拡張子を$extに変えた文字列を用意する
		// 例: "000_ermanga00001.txt" -> "000_ermanga00001.jpg"
		// その画像があれば、開いて、画像の幅と高さを取得する。
		// 横幅が広ければ$imgwにhを入れる。
		$img_filename = str_replace(".txt", trim($ext), $orig_filename);
		$imgp = "";
		if (file_exists($img_filename)) {
			$img_info = getimagesize($img_filename);
			if ($img_info !== false) {
				$imgw = $img_info[0];
				$imgh = $img_info[1];
				if ($imgw > $imgh) {
					$imgp = ",param=h";
				}
			}
		}
		
		while (($line = fgets($handle)) !== false) {
			$line = chop($line);
			$line = delete_strings($line, $del_str_list);
			if ($idx == 0) {
				print "<?php \n";
			}
			$str = '$base_scn_array[] = ' . sprintf("\"prefix=%03d_%s%s%%%%%%%s\";\n", $idx, $fname, $imgp, $line);
			print $str;
			$idx++;
		}
		fclose($handle);
	}
}
