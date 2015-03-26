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
    <a href="inventory.php"> Inventory </a> <br>
    <a href="sales.php"> Sales </a> <br>
    <a href="customers.php"> Customers </a> <br>
    <a href="part.php"> Part Lookup </a> <br>
    <b> Car Lookup </b> <br>
    </div>
    <div id=textBody>
        <h2> Car Lookup </h2>
      <?php

        require('connect.php');

         print "Search for a car (case sensitive): <form method=post action=car.php> Make: <input type=text name=MAKE> Model: <input type=text name=MODEL><input type=submit value='Search'></form>";


         if (array_key_exists('MAKE', $_POST))
         {

         if ($_POST['MAKE']!=NULL && $_POST['MODEL']!=NULL)
         {
          $partQuery=oci_parse($conn, "select * from car where make like '%".$_POST['MAKE']."%' and model like '%".$_POST['MODEL']."%' order by 2,3 asc ");
        }
          elseif ($_POST['MAKE']!=NULL && $_POST['MODEL']==NULL)
          {
          $partQuery=oci_parse($conn, "select * from car where make like '%".$_POST['MAKE']."%' order by 2,3 asc ");
          }
          elseif ($_POST['MAKE']==NULL && $_POST['MODEL']!=NULL)
          {
          $partQuery=oci_parse($conn, "select * from car where model like '%".$_POST['MODEL']."%' order by 2,3 asc ");
          }
         else{
          $partQuery=oci_parse($conn, "select * from car order by 2,3 asc ");
          }
          oci_execute($partQuery);

          print "<table cellpadding=8 cellspacing=3 ><tr><th bgcolor=F5F5CD> Vin Num </th> <th bgcolor=F5F5CD> Make </th> <th bgcolor=F5F5CD> Model </th><th bgcolor=F5F5CD> Year</th><th bgcolor=F5F5CD> Color </th><th bgcolor=F5F5CD> Mileage </th></tr>";

          while ($info=oci_fetch_row($partQuery))
          {
            print "<tr><td bgcolor=F5F5CD>".$info[0]."</td><td bgcolor=F5F5CD>".$info[1]."</td><td bgcolor=F5F5CD>".$info[2]."</td><td bgcolor=F5F5CD>".$info[3]."</td><td bgcolor=F5F5CD>".$info[4]."</td><td bgcolor=F5F5CD>".$info[5]."</td></tr>";
          }

          print "</table>";
         }

     ?>
    </div>
    </div>
  </body>
</html>