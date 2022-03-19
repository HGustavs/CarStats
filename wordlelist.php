<html>
	<head>
		<title>PHP Test</title>
	</head>

<body>

<pre>

<?php 

$log_db = new PDO('sqlite:./GHdata.db');
$sql = 'CREATE TABLE IF NOT EXISTS  (id INTEGER PRIMARY KEY,WORD VARCHAR(10));';
$log_db->exec($sql);

$handle = fopen("wordlelist.csv", "r");
$arr=Array();
if ($handle) {
    $i=0;
    while (($line = fgets($handle)) !== false) {
 				 $items=explode ("\t",$line);
         if($i++==2319){
            shuffle($arr);
         }
         if($i>1) array_push($arr,trim($items[2]));
    }

    fclose($handle);

} else {
    // error opening the file.
} 

$i=0;
echo "var list=[\n";
foreach($arr as $word){
    if($i++>0) echo ",";
    if($i%8==0) echo "\n";
    echo "'".$word."'";
}
echo "\n];";

?>

</pre>