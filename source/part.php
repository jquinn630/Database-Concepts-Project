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
    <b>  Part Lookup </b> <br>
    <a href="car.php"> Car Lookup</a> <br>
    </div>
    <div id=textBody>
        <h2> Part Lookup Table </h2>
      <?php

        require('connect.php');

          print "Search for a part (case sensitive): <form method=post action=part.php><input type=text name=STRING><input type=submit value='Search'></form>";

          if (array_key_exists('STRING', $_POST))
          {
          if ($_POST['STRING']!=NULL)
          {
          $partQuery=oci_parse($conn, "select * from part p where p.part_desc like '%".$_POST['STRING']."%' order by 2 asc ");
          }
          else
          {
          $partQuery=oci_parse($conn, "select part_num, part_desc from part order by 2 asc ");
          }
          oci_execute($partQuery);

          print "<table cellpadding=8 cellspacing=3 ><tr><th bgcolor=F5F5CD> Part Description </th> <th bgcolor=F5F5CD> Part Number </th></tr>";

          while ($info=oci_fetch_row($partQuery))
          {
            print "<tr><td bgcolor=F5F5CD>".$info[1]."</td><td bgcolor=F5F5CD>".$info[0]."</td></tr>";
          }

          print "</table>";
          }

     ?>
    </div>
    </div>
  </body>
</html>