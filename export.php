<?php
	$input = 'Assets - Last view used (example).csv'; // The CSV from podio (you will need to convert from the xlsx file supplied)
	$output = 'Results.csv'; // This is the file you will import into Disco (after you verify there is no dirty data)
	$NewHeadings = array( // These are the disco headings, if you are adding any additional headings then put them here and also update $outputData
		"Device Serial Number",
		"Assigned User Identifier",
		"Model Identifier",
		"Batch Identifier",
		"Device Location",
	);
	$models = array( // These are the model names in podio, Disco takes an identifier so we need to swap them out.
		"X131e Chromebook" => 1,
	);
	$batches = array( // Same as above
		"Parent Purchase" => 1,
	);
	$other = array( // And again
		"#N/A" => "",
	);

	
	Write("EXPORTING PODIO ASSETS:<br/>");
	Write("Checking File permisions...");
	if (!is_writable($input) ) {Write("Failed.", true);}else{Write("OK");}
	Write("Opening ".$input);
	$er = false;
	if (false !== ($ih = fopen($input, 'r'))) {
		Write("Done.");
		Write("Altering column positioning...");
		$oh = fopen($output, 'w');
		while (false !== ($data = fgetcsv($ih))) {
			$data[8] = strtoupper($data[8]); // Capitalize usernames
			$outputData = array($data[3], $data[8], $data[9], $data[10], $data[11]); // Alters the columns (change this if you are changing $NewHeadings)
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
	$arr = preg_replace("/\b0\b/", "", $arr); // Remove individual zeros
	$arr = str_replace(",-,", ",,", $arr); // Remove individual dashes
	Write("Done.");
	Write("Saving to ".$output."...");
	$put = file_put_contents($output, implode($arr));
	if($put){Write("Done.");}else{Write("Failed.", true);}
	Write("<br/>FINISHED.");
	function Write($str = "", $err = false){echo"<span style='color:";if(isset($err) and $err){echo"red";}else{echo"green";} echo";'>".$str."</span><br/>\n";}
?>
