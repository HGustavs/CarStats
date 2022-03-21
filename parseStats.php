<html>
  <body>
    <pre>
<?php
$file = fopen("Stats.csv","r");

$log_db = new PDO('sqlite:./CarStats.db');
$sql = 'CREATE TABLE IF NOT EXISTS RegData(id INTEGER PRIMARY KEY,period DATETIME,kind VARCHAR(30),make VARCHAR(30),model VARCHAR(30),grp VARCHAR(30),cnt INTEGER);';
$log_db->exec($sql);

$thegroup=[
  "VW","VOLVO","BMW","TOYOTA","KIA","AUDI","SKODA","RENAULT","FORD","NISSAN",
  "MERCEDES","HYUNDAI","PEUGEOT","OPEL","MAZDA","DACIA","CITROEN","SUBARU","HONDA","FIAT",
  "SEAT","MITSUBISHI","SUZUKI","CHEVROLET","LEXUS","MINI","LAND ROVER","LANCIA","PORSCHE","JEEP",
  "JAGUAR","ALFA ROMEO","TESLA","SMART","DODGE","LADA","MASERATI","ISUZU","IVECO","SSANGYONG",
  "BENTLEY","ASTON MARTIN","CADILLAC","MAN","LAMBORGHINI","MORGAN","FERRARI","LOTUS","ROLLS-ROYCE","DS",
  "NEVS","MAYBACH","Alpine","Polestar","LYNK & CO","MAXUS","MG","MCLAREN","TRI-STAR"
];
$ingroup=[
  "VW","Geely","BMW","Toyota","Hyundai Motor Group","VW","VW","Renault-Nissan-Mitsubishi Alliance","Ford","Renault-Nissan-Mitsubishi Alliance",
  "Mercedes-Benz Group","Hyundai Motor Group","Stellantis","Stellantis","Mazda","Renault-Nissan-Mitsubishi Alliance","Stellantis","Subaru Corporation","Honda Motor Company","Stellantis",
  "VW","Renault-Nissan-Mitsubishi Alliance","Suzuki Motor Corporation","GM","Toyota","BMW","Tatra","Stellantis","VW","Stellantis",
  "Tatra","Stellantis","Tesla","Mercedes-Benz Group","Stellantis","Renault-Nissan-Mitsubishi Alliance","Stellantis","Isuzu Motors","Iveco Group","Edison Motors",
  "VW","ASTON MARTIN","GM","VW","VW","Morgan Motor Company","Stellantis","Geely","BMW","Stellantis",
  "NEVS","Mercedes-Benz Group","Renault-Nissan-Mitsubishi Alliance","Geely","Geely","SAIC Maxus Automotive","SAIC Maxus Automotive","Mclaren","TRI-STAR"
];

$i=0;
while(! feof($file)){
    $str=fgets($file);
    $arr=explode(",",$str);
    if($i>0){
        $kind=$arr[1];
        $date=$arr[2]."-01";
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
        if($make=="LAND"||$make=="LR"||$make=="ALFA"||$make=="ROLLS"||$make=="ASTON"||$make=="LYNK"){
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
            }else if(strpos($txt,"ROLLS ROYCE")!==false){
                $make="ROLLS-ROYCE";
                $model=substr(trim($txt),12);
            }else if(strpos($txt,"LYNK & CO")!==false){
                $make="LYNK & CO";
                $model=substr(trim($txt),9);
            }else if(strpos($txt,"LR")!==false){
                $make="LAND ROVER";
                $model=substr(trim($txt),5);
            }else{
                echo "NO MATCH: ".$make."**".$model."**";
            }
        }else if(strpos($make,"AMAT")!==false){
            $model="FABRIKAT";
        }

        // Test for Group
        $groupid=array_search($make,$thegroup);
        if($make!="VW" && $make!="BMW") $make=ucfirst(strtolower($make));
        $model=ucfirst(strtolower($model));
        if($model=="Fabrikat"||$make==""||strpos($txt,"BYGGE")!==false){

        }else if($groupid!==false){
            $group=$ingroup[$groupid];
            // echo $i." ".$date." ".$kind." ".$count." ".$make." ".$model." ".$group." ".$txt."\n";

            $query = $log_db->prepare('INSERT INTO RegData(id,kind,period,make, model, grp, cnt) VALUES (:id,:kind,:period,:make,:model,:grp,:cnt)');

            $query->bindParam(':id', $i);
            $query->bindParam(':kind', $kind);
            $query->bindParam(':period', $date);
            $query->bindParam(':make', $make);
            $query->bindParam(':model', $model);
            $query->bindParam(':grp', $grp);
            $query->bindParam(':cnt', $count);
            $query->execute();            
        }else{
            echo "NO GROUP: ".$make."\n";
            print_r($arr);
            $group="UNK";
        }

        
    }
    $i++;
}
fclose($file);
?> 
    </pre>
  </body>
</html>