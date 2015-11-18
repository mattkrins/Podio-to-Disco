<?php
	$input = 'Assets - Last view used.csv';
	$output = 'Results.csv';
	$NewHeadings = array(
		"Device Serial Number",
		"Assigned User Identifier",
		"Model Identifier",
		"Batch Identifier",
		"Device Location",
	);
	$models = array(
		"11e Chromebook" => 5,
		"X131e Chromebook" => 6,
		"Acer P446M" => 10,
		"Macbook Pro 13" => 12,
		"Macbook White" => 13,
		"Custom Build" => 4,
		"iMac 27" => 11,
		"L430" => 20,
		"L420" => 14,
		"L410" => 15,
		"L412" => 19,
		"L510" => 18,
		"L512" => 21,
		"X220i" => 7,
		"X230i" => 9,
		"X230" => 8,
		"M71e" => 17,
		"A62" => 16,
	);
	$batches = array(
		"Parent Purchase" => 1,
		"Private Purchase" => 18,
		"School Purchased Parts" => 26,
		"RND1CYC5" => 19,
		"RND1CYC6" => 20,
		"RND2CYC5" => 21,
		"RND3CYC5" => 22,
		"RND4CYC4" => 23,
		"RND4CYC5" => 24,
		"RND5CYC4" => 25,
		"P000334" => 13,
		"P004858" => 14,
		"P005636" => 15,
		"P005973" => 16,
		"P006335" => 17,
		"13504" => 2,
		"14560" => 3,
		"15483" => 4,
		"16867" => 6,
		"16136" => 5,
		"19135" => 7,
		"19200" => 8,
		"21896" => 10,
		"2994" => 12,
		"2756" => 11,
		"2085" => 9,
	);
	$other = array(
		"#N/A" => "",
	);

	
	Write("EXPORTING PODIO ASSETS:<br/>");
	Write("Checking File permisions...");
	function Write($str = "", $err = false){echo"<span style='color:";if(isset($err) and $err){echo"red";}else{echo"green";} echo";'>".$str."</span><br/>\n";}
	if (!is_writable($input) ) {Write("Failed.", true);}else{Write("OK");}
	Write("Opening ".$input);
	$er = false;
	if (false !== ($ih = fopen($input, 'r'))) {
		Write("Done.");
		Write("Altering column positioning...");
		$oh = fopen($output, 'w');
		while (false !== ($data = fgetcsv($ih))) {
			$data[8] = strtoupper($data[8]); // capitalize usernames
			$outputData = array($data[3], $data[8], $data[9], $data[10], $data[11]); // Alters the columns
			$go = fputcsv($oh, $outputData);
			if(!$go){$er = true;}
		}
		if($er){Write("Failed.", true);}else{Write("Done.");}
		fclose($ih);
		fclose($oh);
	}else{Write("Failed.", true);}
	Write("Swapping headings...");
	$head = "";$count = 0;$last = count($NewHeadings);
	foreach ($NewHeadings as &$v) {
		$count++;
		if($last!=$count){$head = $head.$v.",";}else{$head = $head.$v;$head.="\n";}
	}
	Write("Done.");
	$arr = file($output);
	$arr[0] = $head;
	$error = false;
	while (list($k, $v) = each($models)) {$arr = str_replace($k,$v,$arr);}
	while (list($k, $v) = each($batches)) {$arr = str_replace($k,$v,$arr);}
	while (list($k, $v) = each($other)) {$arr = str_replace($k,$v,$arr);}
	Write("Removing Zeros and Dashes...");
	$arr = preg_replace("/\b0\b/", "", $arr); // remove individual zeros
	$arr = str_replace(",-,", ",,", $arr); // remove individual dashes
	Write("Done.");
	Write("Saving to ".$output."...");
	$put = file_put_contents($output, implode($arr));
	if($put){Write("Done.");}else{Write("Failed.", true);}
	Write("<br/>FINISHED.");
?>
