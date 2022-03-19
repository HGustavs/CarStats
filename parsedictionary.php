<?php
$file = fopen("dictionary.txt","r");

echo "var words=[";

$i=0;
while(! feof($file)){
  $str=fgets($file);
	if(strpos($str,"'")===false&&strpos($str,"+")===false){
			echo '"'.trim($str).'"';
			if($i>0) echo ",";
			$i++;
			if($i%50==0) echo "\n";
	}
}
echo "];";
fclose($file);
?> 