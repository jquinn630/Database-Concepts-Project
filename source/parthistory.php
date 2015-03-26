<html>
  <head>
    <link href="style.css" rel="stylesheet" type="text/css">
  </head>
  <body>  
    <div id=topBar>
    <h2> WIRTHMAN BROS AUTO </h2>
    </div>
    <div id=linksMenu>
    <a href="index.php"> Home </a>  <br>
    <a href="history.php"> History </a> <br>
    <a href="inventory.php"> Inventory </a>  <br>
    <a href="sales.php"> Sales </a> <br>
    <a href="customers.php"> Customers </a> <br>
        <a href="part.php" >  Part Lookup </a> <br>
            <a href="car.php" > Car Lookup </a> <br>

    </div>
    <div id=textBody>
      <h2> Part History </h2>
     <?php

        require("connect.php");

        if (array_key_exists('VINNUM',$_POST))
        {
            print "<h2> Current Inventory </h2>";
            print "<table cellpadding=5 cellspacing=5><tr><th bgcolor=F5F5DC> Part Number </th><th bgcolor=F5F5DC> Part Description </th><th bgcolor=F5F5DC> VIN Number </th> <th bgcolor=F5F5DC> Car Make </th><th bgcolor=F5F5DC> Car Model </th><th bgcolor=F5F5DC> Year </th><th bgcolor=F5F5DC> Color </th><th bgcolor=F5F5DC> Mileage </th>";
            print "</tr>";
            
            $partQuery=oci_parse($conn, "select part_desc from part where part_num=".$_POST['PARTNUM']."");  
            oci_execute($partQuery);

            $carQuery=oci_parse($conn, "select * from car where vin_num='".$_POST['VINNUM']."'");
            oci_execute($carQuery);
          
            $partdesc=oci_fetch_row($partQuery);
            $carstuff=oci_fetch_row($carQuery);

            print "<tr>";
            print "<td bgcolor=F5F5DC>".$_POST['PARTNUM']."</td>";
            print "<td bgcolor=F5F5DC>".$partdesc[0]."</td>";
            print "<td bgcolor=F5F5DC>".$carstuff[0]."</td>";
            print "<td bgcolor=F5F5DC>".$carstuff[1]."</td>";
            print "<td bgcolor=F5F5DC>".$carstuff[2]."</td>";
            print "<td bgcolor=F5F5DC>".$carstuff[3]."</td>";
            print "<td bgcolor=F5F5DC>".$carstuff[4]."</td>";
            print "<td bgcolor=F5F5DC>".$carstuff[5]."</td>";
            print "</tr>";
        
            print "</table>";
          }

  
    ?>

    </div>
    </div>
  </body>
</html>