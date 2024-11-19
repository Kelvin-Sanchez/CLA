<?php
// error_reporting(E_ALL);
// current time
date_default_timezone_set('America/New_York');
echo "Processing Started at ";
echo date('h:i:s') . "\n";

// sleep for 3 seconds
sleep(1);


function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad(floatval($lat1))) * sin(deg2rad(floatval($lat2))) + cos(deg2rad(floatval($lat1))) * cos(deg2rad(floatval($lat2))) * cos(deg2rad(floatval($theta)));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);
    if ($unit == "K") {
        return ($miles * 1.609344);
    } else if ($unit == "M") {
        return ($miles * 0.8684);
    } else {
        return round($miles);
    }
}
// set_time_limit(0);
ini_set('max_execution_time', 600); // 10 minutes
$file = file('Costco NE to Import.csv');
$file2 = file('2NEW_Geocoded USA Stores 2019-12C.csv');

$outfile = fopen('Results-New Costco NE to Import.csv','w');

array_shift($file);
array_shift($file2);


$headers = array('Customer','Store Name','Address','City','State','Zip','Phone','to','Customer','Store Name','Address','City','State','Zip','Phone','Distance');

fputcsv($outfile,$headers);
$outline = 0;
$inline = 0;

do {list($lat1,$lon1,$mat1,$cus1,$str1,$add1,$cit1,$sta1,$zip1,$phone1) = explode(',',array_shift($file));
	echo "Outside: ".$outline."\n";
	$outline = $outline + 1;
foreach ($file2 as $data) {
	echo "Inside: ".$inline."\n";
	$inline = $inline + 1;
	list($lat2,$lon2,$mat2,$cus2,$str2,$add2,$cit2,$sta2,$zip2,$phone2) = explode(',', $data);
	$dis = distance($lat1, $lon1, $lat2, $lon2, "M");
	
	$line = array($cus1,$str1,$add1,$cit1,$sta1,$zip1,$phone1,"to",$cus2,$str2,$add2,$cit2,$sta2,$zip2,$phone2,$dis);
	//echo $lat1." ".$lon1;	
	if ($dis <= 25 && $cus1!=NULL)
	{ 
		fputcsv($outfile, $line);}

							};
	}while($file != NULL);

fclose($outfile);
echo "and Completed at ";
echo date('h:i:s') . "\n";

?>
