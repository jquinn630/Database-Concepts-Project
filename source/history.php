<html>
  <head>
    <link href="style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div id=mainBody>
    <div id=topBar>
    <h2> WIRTHMAN BROS AUTO </h2>
    </div>
    <div id=linksMenu>
    <a href="index.php"> Home </a>  <br>
    <b> History </b> <br>
    <a href="inventory.php"> Inventory </a> <br>
    <a href="sales.php"> Sales </a> <br>
    <a href="customers.php"> Customers </a> <br>
    <a href="part.php" >  Part Lookup </a> <br>
    <a href="car.php" > Car Lookup </a> <br>
    </div>
    <div id=textBody>
        <h2> Inventory And Part History </h2>
      <?php

        require('connect.php');

        print "<table><tr><td> Browse Inventory </td><td> <form method=post action=history.php> <br><select name=BROWSEOPTION><option value=CURRENT> Current Inventory </option> <option value=PAST> Past Inventory </option><option value=ALLTIME> All Time Inventory </option></select> </td><td><input type=submit value='Show Inventory'></form></td></tr></table> <br>";


        if (array_key_exists('BROWSEOPTION',$_POST))
        {

          $viewOpt=$_POST['BROWSEOPTION'];

          if ($viewOpt=="CURRENT")
          {
            print "<h2> Current Inventory </h2>";
            print "<table cellpadding=5 cellspacing=5><tr><th bgcolor=F5F5DC> VIN Number </th><th bgcolor=F5F5DC> Part Number </th><th bgcolor=F5F5DC> Part Description </th><th bgcolor=F5F5DC> Location</th> <th bgcolor=F5F5DC> COST </th><th bgcolor=F5F5DC> History </th>";
            $viewQuery=oci_parse($conn, "select * from inventory where in_stock=1 order by 1 asc");  
            oci_execute($viewQuery);
            print "</tr>";
            while($info=oci_fetch_row($viewQuery))
            {
             $partQuery=oci_parse($conn, "select part_desc from part where part_num=".$info[1]."");
              oci_execute($partQuery);
              $partdesc=oci_fetch_row($partQuery);
              print "<tr>";
              print "<td bgcolor=F5F5DC>".$info[0]."</td>";
              print "<td bgcolor=F5F5DC>".$info[1]."</td>";
              print "<td bgcolor=F5F5DC>".$partdesc[0]."</td>";              
              print "<td bgcolor=F5F5DC>".$info[2]."</td>";
              print "<td bgcolor=F5F5DC>".$info[3]."</td>";
              print "<td bgcolor=F5F5DC align=center><form method=post action=parthistory.php><input type=hidden name=PARTNUM value=".$info[1]."><input type=hidden name=VINNUM value=".$info[0]."><input type=submit value='View Part History'></form></td>";
              print "</tr>";
            }
            print "</table>";
          }
          else if ($viewOpt=="PAST")
          {
            print "<h2> Past Inventory </h2>";
            print "<table cellpadding=5 cellspacing=5><tr><th bgcolor=F5F5DC> VIN Number </th><th bgcolor=F5F5DC> Part Number </th><th bgcolor=F5F5DC> Part Description </th><th bgcolor=F5F5DC> Location</th> <th bgcolor=F5F5DC> COST </th><th bgcolor=F5F5DC> History </th>";
            $viewQuery=oci_parse($conn, "select * from inventory where in_stock=0 order by 1 asc");  
            oci_execute($viewQuery);
            print "</tr>";
            while($info=oci_fetch_row($viewQuery))
            {
              $partQuery=oci_parse($conn, "select part_desc from part where part_num=".$info[1]."");
              oci_execute($partQuery);
              $partdesc=oci_fetch_row($partQuery);
              print "<tr>";
              print "<td bgcolor=F5F5DC>".$info[0]."</td>";
              print "<td bgcolor=F5F5DC>".$info[1]."</td>";
              print "<td bgcolor=F5F5DC>".$partdesc[0]."</td>";              
              print "<td bgcolor=F5F5DC>".$info[2]."</td>";
              print "<td bgcolor=F5F5DC>".$info[3]."</td>";
              print "<td bgcolor=F5F5DC align=center><form method=post action=parthistory.php><input type=hidden name=PARTNUM value=".$info[1]."><input type=hidden name=VINNUM value=".$info[0]."><input type=submit value='View Part History'></form></td>";
              print "</tr>";
            }
            print "</table>";
          }
          else if ($viewOpt=="ALLTIME")
          {
            print "<h2> All Time Inventory </h2>";
            print "<table cellpadding=5 cellspacing=5><tr><th bgcolor=F5F5DC> VIN Number </th><th bgcolor=F5F5DC> Part Number </th><th bgcolor=F5F5DC> Part Description </th><th bgcolor=F5F5DC> Location</th> <th bgcolor=F5F5DC> COST </th><th bgcolor=F5F5DC> In Stock </th><th bgcolor=F5F5DC> History </th>";
            $viewQuery=oci_parse($conn, "select * from inventory order by 1 asc");  
            oci_execute($viewQuery);
            print "</tr>";
            while($info=oci_fetch_row($viewQuery))
            {
              $partQuery=oci_parse($conn, "select part_desc from part where part_num=".$info[1]." order by 1 asc");
              oci_execute($partQuery);
              $partdesc=oci_fetch_row($partQuery);
              print "<tr>";
              print "<td bgcolor=F5F5DC>".$info[0]."</td>";
              print "<td bgcolor=F5F5DC>".$info[1]."</td>";
              print "<td bgcolor=F5F5DC>".$partdesc[0]."</td>";              
              print "<td bgcolor=F5F5DC>".$info[2]."</td>";
              print "<td bgcolor=F5F5DC>".$info[3]."</td>";
              if ($info[4]==1) print "<td bgcolor=F5F5DC> YES </td>";
              else if ($info[4]==0) print "<td bgcolor=F5F5DC> NO </td>";
              print "<td bgcolor=F5F5DC align=center><form method=post action=parthistory.php><input type=hidden name=PARTNUM value=".$info[1]."><input type=hidden name=VINNUM value=".$info[0]."><input type=submit value='View Part History'></form></td>";
              print "</tr>";
            }
            print "</table>";

          }

        }

     ?>
    </div>
    </div>
  </body>
</html>