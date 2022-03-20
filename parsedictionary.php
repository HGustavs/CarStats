<html>
  <body>
    <pre>
<?php
$file = fopen("Stats.csv","r");

$log_db = new PDO('sqlite:./CarStats.db');
$sql = 'CREATE TABLE IF NOT EXISTS  RegData(id INTEGER PRIMARY KEY,period DATETIME,make VARCHAR(30),model VARCHAR(30),cnt INTEGER);';
$log_db->exec($sql);

$i=0;
while(! feof($file)){
    $str=fgets($file);
    $arr=explode(",",$str);
    if($i>0){
        $kind=$arr[1];
        $date=$arr[2];

        $txt=$arr[5];

        echo $kind." ".$txt."\n";
    }
    if($i++==10) break;
}
fclose($file);
?> 
    </pre>
  </body>
</html>