<html>
  <body>
    <pre>
<?php

$log_db = new PDO('sqlite:./CarStats.db');

// Retrieve full database and swizzle into associative array for each day
$query=$log_db->prepare('SELECT * FROM regdata where model like "XC60%" and kind="Hybrid" order by periodi limit 100;');
// $query->bindParam(':hash', $hash);
// $query->bindParam(':admincode', $admincode);		
if (!$query->execute()) {
    $error = $log_db->errorInfo();
    print_r($error);
}else{
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);	
    foreach($rows as $row){
        echo $row['period']." ".$row['cnt']." ".$row['model']."\n";
    }
}

?> 
    </pre>
  </body>
</html>