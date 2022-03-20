<html>
  <body>
    <pre>
<?php
$file = fopen("Stats.csv","r");

$log_db = new PDO('sqlite:./CarStats.db');
$sql = 'CREATE TABLE IF NOT EXISTS RegData(id INTEGER PRIMARY KEY,period DATETIME,make VARCHAR(30),model VARCHAR(30),grp VARCHAR(30),cnt INTEGER);';
$log_db->exec($sql);

$thegroup=[
  "VW","VOLVO","BMW","TOYOTA","KIA","AUDI","SKODA","RENAULT","FORD","NISSAN",
  "MERCEDES","HYUNDAI","PEUGEOT","OPEL","MAZDA","DACIA","CITROEN","SUBARU","HONDA","FIAT",
  "SEAT","MITSUBISHI","SUZUKI","CHEVROLET","LEXUS","MINI","LAND ROVER","LANCIA","PORSCHE","JEEP",
  "JAGUAR","ALFA ROMEO","TESLA","SMART","DODGE","LADA","MASERATI","ISUZU","IVECO","SSANGYONG",
  "BENTLEY","ASTON MARTIN","CADILLAC","MAN"
];
$ingroup=[
  "VW","Geely","BMW","Toyota","Hyundai Motor Group","VW","VW","Renault-Nissan-Mitsubishi Alliance","Ford","Renault-Nissan-Mitsubishi Alliance",
  "Mercedes-Benz Group","Hyundai Motor Group","Stellantis","Stellantis","Mazda","Renault-Nissan-Mitsubishi Alliance","Stellantis","Subaru Corporation","Honda Motor Company","Stellantis",
  "VW","Renault-Nissan-Mitsubishi Alliance","Suzuki Motor Corporation","GM","Toyota","BMW","Tatra","Stellantis","VW","Stellantis",
  "Tatra","Stellantis","Tesla","Mercedes-Benz Group","Stellantis","Renault-Nissan-Mitsubishi Alliance","Stellantis","Isuzu Motors","Iveco Group","Edison Motors",
  "VW","ASTON MARTIN","GM","VW"
];

$i=0;
while(! feof($file)){
    $str=fgets($file);
    $arr=explode(",",$str);
    if($i>0){
        $kind=$arr[1];
        $date=$arr[2];
        $count=$arr[4];

        if(!isset($arr[7])){
          $txt=trim($arr[6]);
        }else{
          $txt=trim($arr[7]);          
        }

        if(strpos($txt," ")===false){
            $make=$txt;
            $model="UNK";
        }else{
            $make=substr($txt,0,strpos($txt," "));
            $model=substr($txt,strpos($txt," ")+1);
        }

        if($model=="UNK"&&strpos($txt,"MAZDA")===0){
            $make=substr($txt,0,5);
            $model=substr($txt,5);
        }

        // Test for Multiple Word Makers
        if(strpos($make,"LAND")!==false||strpos($make,"LR")!==false||strpos($make,"ALFA")!==false||strpos($make,"ASTON")!==false){
            // echo "<div style='color:red'>".$arr[7]."</div>";
            if(strpos($txt,"LAND ROVER")!==false){
                $make="LAND ROVER";
                $model=substr(trim($txt),11);
            }else if(strpos($txt,"ALFA ROMEO")!==false){
                $make="ALFA ROMEO";
                $model=substr(trim($txt),11);
            }else if(strpos($txt,"ASTON MARTIN")!==false){
                $make="ASTON MARTIN";
                $model=substr(trim($txt),12);
            }else if(strpos($txt,"LR")!==false){
                $make="LAND ROVER";
                $model=substr(trim($txt),5);
            }else{
                echo "NO MATCH: ".$make."**".$model."**";
            }
        }

        // Test for Group
        $groupid=array_search($make,$thegroup);
        if($groupid!==false){
            $group=$ingroup[$groupid];
            echo $i." ".$date." ".$kind." ".$count." ".$make." ".$model." ".$group." ".$txt."\n";
        }else if($model=="FABRIKAT"||$model==""){
        }else{
            echo "NO GROUP: ".$make."\n";
            print_r($arr);
            $group="UNK";
        }

        
    }
    if($i++==100) break;
}
fclose($file);
?> 
    </pre>
  </body>
</html>