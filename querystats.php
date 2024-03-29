<html>
  <head>
    <script>

    function showData()
    {
        var str="";
        str+="<line x1='10' y1='10' x2='10' y2='390' stroke='black' />";
        str+="<line x1='10' y1='390' x2='590' y2='390' stroke='black' />";

        var colors=["#B52","#25B","#2B5","#BB5","#2AA","#B5B","#AAA"];

        // Compute sum of all cars except for xc60 and place in data as total
        var sum=[];
        for(var i=0;i<98;i++){
            var csum=0;
            for (var key in data){
                if(key!="xc60"){
                    var list=data[key];
                    for(var j=0;j<list.length;j++){
                        var item=list[j];
                        if(item.periodi==i){
                            csum+=item.cnt;
                        }
                    }
                }
            }
            sum.push({cnt:csum,periodi:i});
        }
        data['total']=sum;

        var c=0;
        var roll=8;
        for (var key in data){
            var col=colors[c];
            c++;
            var list=data[key];
            var cx=0;
            var cy=0;
            for(var i=0;i<list.length;i++){
                var item=list[i];
                var ox=cx;
                var oy=cy;

                var val=0;
                for(var j=0;j<roll;j++){
                    if((i-j)>=0){
                        val+=list[i-j].cnt;
                    }else{
                        val+=list[0].cnt;
                    }
                }
                val=val/roll;

                var cx=10+(item.periodi*6);
                var cy=390-((val/2500)*300);

                if(ox!=0&&oy!=0){
                    str+="<line x1='"+ox+"' y1='"+oy+"' x2='"+cx+"' y2='"+cy+"' stroke='"+col+"' stroke-width='1.5' />";
                }
                str+="<circle cx='"+cx+"' cy='"+cy+"' r='3' fill='"+col+"' />";

            }           
        }

        document.getElementById("outp").innerHTML=str;
    }
  <?php

$log_db = new PDO('sqlite:./CarStats.db');

function makeVehicles($inmodel,$inmake,$inkind)
{
    global $log_db;
    $query=$log_db->prepare('SELECT * FROM regdata where make="'.$inmake.'" and model LIKE "'.$inmodel.'" and kind="'.$inkind.'" order by periodi;');
    
    // echo 'SELECT * FROM regdata where make="'.$inmake.'" and model LIKE "'.$inmodel.'" and kind="'.$inkind.'" order by periodi;';
    
    if (!$query->execute()) {
        $error = $log_db->errorInfo();
        print_r($error);
    }else{
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;	
    }
}

$arr=[];

$arr['id3']=makeVehicles("%Id.3%","VW","Elbil");
$arr['id4']=makeVehicles("%Id.4%","VW","Elbil");
$arr['xc60']=makeVehicles("%Xc60%","Volvo","Samtlig");
$arr['model3']=makeVehicles("%Model 3%","Tesla","Elbil");
$arr['modelY']=makeVehicles("%Model Y%","Tesla","Elbil");
$arr['polestar']=makeVehicles("%2%","Polestar","Elbil");

echo "var data=";
echo json_encode($arr);
echo ";";

?>   
  </script>
  </head>
  <body onload="showData();">

    <svg id='outp' viewBox="0 0 600 400" style='border:1px dotted green;' width="900" height="600" xmlns="http://www.w3.org/2000/svg">
    </svg>
  </body>
</html>