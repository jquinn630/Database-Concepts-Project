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
    <a href="history.php"> History </a> <br>
    <b> Inventory </b> <br>
    <a href="sales.php"> Sales </a> <br>
    <a href="customers.php"> Customers </a> <br>
    <a href="part.php" >  Part Lookup </a> <br>
        <a href="car.php" > Car Lookup </a> <br>

    </div>
    <div id=textBody>
     <?php

        require("connect.php");

        if (array_key_exists('ADDPART', $_POST))
        {
          $partQuery=oci_parse($conn, "select * from part where part_num=".$_POST['PARTNUM']."");
          oci_execute($partQuery);
          $check=oci_fetch_row($partQuery);
          $carQuery=oci_parse($conn, "select * from car where vin_num='".$_POST['VINNUM']."'");
          oci_execute($carQuery);
          $carExists=oci_fetch_row($carQuery);
          $invQuery=oci_parse($conn, "select * from inventory where part_num=".$_POST['PARTNUM']." and vin_num='".$_POST['VINNUM']."'");
          oci_execute($invQuery);
          $isInv=oci_fetch_row($invQuery);

          if ($_POST['VINNUM']==NULL||$_POST['PARTNUM']==NULL||$_POST['PARTDESC']==NULL||$_POST['LOCATION']==NULL||$_POST['COST']==NULL)
          {
            print "<i> Error: you must enter data into all of the 'Add a Part' fields to add a part. </i>";
          }

          elseif (!is_numeric($_POST['VINNUM']) || !is_numeric($_POST['PARTNUM']) || !is_numeric($_POST['COST']))
          {
            print "<i> Error: cost, vin number, and part number must all be numeric. </i>";
          }

          elseif ($carExists[0]==NULL)
          {
            print "<i> Error: there is no car in the system with that vin number.  Please add the car information before adding the part. </i>";
          }

          elseif ($check[1]!=$_POST['PARTDESC']  && $check[1]!=NULL)
          {
            print "<i> Error: part description and part number combination do not match what is already in the system.  Part numbers are unique to each part.  Note that part description is case sensitive.  For help, visit the Part Lookup page. </i>";
          }

          elseif ($isInv[0]!=NULL)
          {
            print "<i> Error: that part has already been added to the system.  Either it is still in the inventory or it was sold. </i>";
          }

          else {

          if ($check[0]==NULL)
          {
            $addPart=oci_parse($conn, "insert into part values ( ".$_POST['PARTNUM'].", '".$_POST['PARTDESC']."' )");
            oci_execute($addPart);
          }

          $addInv=oci_parse($conn, "insert into inventory values ( ".$_POST['VINNUM'].", ".$_POST['PARTNUM'].", '".$_POST['LOCATION']."', ".$_POST['COST'].", 1 )");
          oci_execute($addInv);

          print "<i> Part Added </i>";
        }
        }

        if (array_key_exists('MILEAGE', $_POST))
        {
          $carQuery=oci_parse($conn, "select * from car where vin_num='".$_POST['VINNUM']."'");
          oci_execute($carQuery);
          $carExists=oci_fetch_row($carQuery);

          if ($_POST['VINNUM']==NULL||$_POST['MAKE']==NULL||$_POST['MODEL']==NULL||$_POST['YEAR']==NULL||$_POST['COLOR']==NULL||$_POST['MILEAGE']==NULL)
          {
            print "<i> Error: you must enter data into all of the 'Add a Car' fields to add a car. </i>";
          }

          elseif (!is_numeric($_POST['VINNUM']) || !is_numeric($_POST['YEAR']) || !is_numeric($_POST['MILEAGE']))
          {
            print "<i> Error: vin number, year, and mileage must all be numeric. </i>";
          }

          elseif ($carExists[0]!=NULL)
          {
            print "<i> Error: a car already exists with that vin number. </i>";
          }

          else {
         $addCar=oci_parse($conn, "insert into car values ( ".$_POST['VINNUM'].", '".$_POST['MAKE']."', '".$_POST['MODEL']."', ".$_POST['YEAR'].", '".$_POST['COLOR']."', ".$_POST['MILEAGE']." )");
          oci_execute($addCar);
          print "<i> Car Added </i>";
        }
        }

        print "<h3> Add to Inventory </h3>";
        print "<table cellspacing=1 bgcolor=F5F5CD><tr><td align=center> Add a Part </td><form method=post action=inventory.php> <td> VIN Number:<input type=text name=VINNUM> </td> <td> Part Number: <input type=text name=PARTNUM> </td> <td> Part Description: <input type=text name=PARTDESC> </td> <td> Location: <input type=text name=LOCATION> </td> <td> Cost: <input type=text name=COST></td> <input type=hidden name=ADDPART><td colspan=2 align=center> <input type=submit value='Add Part'> </td> </form></tr>";
        print "<tr> <td align=center> Add a Car </td><form method=post action=inventory.php><td> VIN Number: <input type=text name=VINNUM> </td><td> Make:<input type=text name=MAKE></td> <td> Model: <input type=text name=MODEL> </td> <td> Year:<input type=text name=YEAR> </td> <td> Color: <input type=text name=COLOR> </td> <td> Mileage: <input type=text name=MILEAGE></td> <td> <input type=submit value='Add Car'> </td> </form> </tr></table> ";

        $makeQuery=oci_parse($conn, "select distinct c.make from car c, inventory i where i.vin_num=c.vin_num and i.in_stock=1 order by 1 asc");
        oci_execute($makeQuery);
       
        print "<h3> Search Inventory </h3>";
        print "<table cellspacing=5><tr><td> Search By Car Model </td><td>";
        print "<br><form method=post action=inventory.php><select name=SEARCHOPTION>";

        while($info=oci_fetch_row($makeQuery))
            {
              print "<option value='".$info[0]."'> ".$info[0]."</option>";
            }
        print "</select> <input type=submit value='Search By Make'></form></td></tr></table>";

       if (array_key_exists('SHOWSEARCH', $_POST))
        {
          print "<h2> Search Results </h2>";
          if ($_POST['PARTNUM']==NULL && $_POST['YEAR']==NULL)
          {
            $partQuery=oci_parse($conn, "select c.make, c.model, i.part_num, p.part_desc, i.location, i.cost, i.part_num, i.vin_num from inventory i, part p, car c where p.part_num=i.part_num and i.vin_num=c.vin_num and i.in_stock=1 and c.make='".$_POST['MAKE']."' and c.model='".$_POST['MODEL']."'");
          }
          elseif ($_POST['PARTNUM']!=NULL && $_POST['YEAR']==NULL)
          {
            $partQuery=oci_parse($conn, "select c.make, c.model, i.part_num, p.part_desc, i.location, i.cost, i.part_num, i.vin_num from inventory i, part p, car c where p.part_num=i.part_num and i.vin_num=c.vin_num and i.in_stock=1 and c.make='".$_POST['MAKE']."' and c.model='".$_POST['MODEL']."' and i.part_num='".$_POST['PARTNUM']."'");
          }
          elseif ($_POST['PARTNUM']==NULL && $_POST['YEAR']!=NULL)
          {
            $partQuery=oci_parse($conn, "select c.make, c.model, i.part_num, p.part_desc, i.location, i.cost, i.part_num, i.vin_num from inventory i, part p, car c where p.part_num=i.part_num and i.vin_num=c.vin_num and i.in_stock=1 and c.make='".$_POST['MAKE']."' and c.model='".$_POST['MODEL']."' and c.year='".$_POST['YEAR']."'");
          }
          else
          {
            $partQuery=oci_parse($conn, "select c.make, c.model, i.part_num, p.part_desc, i.location, i.cost, i.part_num, i.vin_num from inventory i, part p, car c where p.part_num=i.part_num and i.vin_num=c.vin_num and i.in_stock=1 and c.make='".$_POST['MAKE']."' and c.model='".$_POST['MODEL']."' and c.year='".$_POST['YEAR']."' and i.part_num='".$_POST['PARTNUM']."' ");
          }

          oci_execute($partQuery);
          $info=oci_fetch_row($partQuery);

          if ($info[0]==NULL)
          {
            print "<i> There are currently no parts in the system that match your search. </i>";
          }

          else
          {
          print "<table cellspacing=5 cellpadding=7><tr><th bgcolor=F5F5DC>Make</th><th bgcolor=F5F5DC>Model</th><th bgcolor=F5F5DC> Part Number </th> <th bgcolor=F5F5DC> Part Description </th> <th bgcolor=F5F5DC> Location </th><th bgcolor=F5F5DC> Cost </th><th bgcolor=F5F5DC>History</th> <th bgcolor=F5F5DC> Sell Part </th> </tr>";
          
            print "<tr>";
            print "<td bgcolor=F5F5DC>".$info[0]." </td>";
            print "<td bgcolor=F5F5DC>".$info[1]." </td>";
            print "<td bgcolor=F5F5DC>".$info[2]." </td>";
            print "<td bgcolor=F5F5DC>".$info[3]." </td>";
            print "<td bgcolor=F5F5DC>".$info[4]." </td>";
            print "<td bgcolor=F5F5DC>".$info[5]." </td>";
            print "<td bgcolor=F5F5DC align=center><br><form method=post action=parthistory.php><input type=hidden name=PARTNUM value=".$info[6]."><input type=hidden name=VINNUM value=".$info[7]."><input type=submit value='View Part History'></form></td>";
            print "<td bgcolor=F5F5DC> Salesperson ID <form method=post action=purchase.php><input type=text name=SPID><input type=hidden name=PARTNUM value=".$info[6]."><input type=hidden name=VINNUM value=".$info[7]."><input type=hidden name=TRANS> <input type=submit value='Make a Sale'></td></form>";
            print "</tr>";

          while($info=oci_fetch_row($partQuery))
          {
            print "<tr>";
            print "<td bgcolor=F5F5DC>".$info[0]." </td>";
            print "<td bgcolor=F5F5DC>".$info[1]." </td>";
            print "<td bgcolor=F5F5DC>".$info[2]." </td>";
            print "<td bgcolor=F5F5DC>".$info[3]." </td>";
            print "<td bgcolor=F5F5DC>".$info[4]." </td>";
            print "<td bgcolor=F5F5DC>".$info[5]." </td>";
            print "<td bgcolor=F5F5DC align=center><br><form method=post action=parthistory.php><input type=hidden name=PARTNUM value=".$info[6]."><input type=hidden name=VINNUM value=".$info[7]."><input type=submit value='View Part History'></form></td>";
            print "<td bgcolor=F5F5DC> Salesperson ID <form method=post action=purchase.php><input type=text name=SPID><input type=hidden name=PARTNUM value=".$info[6]."><input type=hidden name=VINNUM value=".$info[7]."><input type=hidden name=TRANS> <input type=submit value='Make a Sale'></td></form>";
            print "</tr>";
          }
          }

          print "</table>";
        
        }


      if (array_key_exists('SEARCHOPTION', $_POST))
        {
            print "<h2> Search By Make and Model </h2>";
            $modelQuery=oci_parse($conn, "select distinct c.model from car c, inventory i where i.vin_num=c.vin_num and i.in_stock=1 and c.make='".$_POST['SEARCHOPTION']."' order by 1 asc");
            oci_execute($modelQuery);

            print "<table cellspacing=5 cellpadding=5><tr><td bgcolor=F5F5DC> Make:</td><td bgcolor=F5F5DC>".$_POST['SEARCHOPTION']."</td><td bgcolor=F5F5DC>Model:</td>";
            print "<td bgcolor=F5F5DC><br><form method=post action=inventory.php><input type=hidden name=SHOWSEARCH><input type=hidden name=MAKE value='".$_POST['SEARCHOPTION']."' ><select name=MODEL>";
            while($info=oci_fetch_row($modelQuery))
            {
              print "<option value='".$info[0]."'> ".$info[0]."</option>";
            }
            print "</select></td><td bgcolor=F5F5DC> Year: </td><td bgcolor=F5F5DC> <input type=text name=YEAR>";
            print"  </td> <td bgcolor=F5F5DC>Part Number: <input type=text name=PARTNUM> </td> <td bgcolor=F5F5DC> <input type=submit value='Search By Model'></form></td></tr></table>";
        }


    ?>

    </div>
    </div>
  </body>
</html>