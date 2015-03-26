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
    <b> Sales </b> <br>
    <a href="customers.php"> Customers </a> <br>
     <a href="part.php" >  Part Lookup </a> <br>
         <a href="car.php" > Car Lookup </a> <br>

    </div>
    <div id=textBody>
    <h2> Sale Information: </h2>

     <?php
        require("connect.php");
        $theQuery=oci_parse($conn, "select * from (select c.cust_id, SUM(s.price) from customers c, sales s where s.cust_id=c.cust_id group by c.cust_id order by 2 desc) where rownum<11");
        oci_execute($theQuery);
        
        print "<table align=center cellpadding=30><tr><td valign=top>";
        print "<h3> Top Customers </h3>";
        print "<table cellspacing=5 cellpadding=5 bgcolor=F5F5CD><tr><th> First</th><th> Last</th> <th> Total Spent </th></tr>";
        while($info=oci_fetch_row($theQuery))
        {
          $custQuery=oci_parse($conn, "select first, last from customers where cust_id=".$info[0]."");
          oci_execute($custQuery);
          $cust=oci_fetch_row($custQuery);

          print "<tr><td>".$cust[0]."</td><td>".$cust[1]."</td><td>$".number_format($info[1],2)."</td></tr>";
        }

        print "</table></td><td valign=top><h3> Top Salespeople </h3>";
        $spQuery=oci_parse($conn, "select * from (select sp.sp_id, NVL(SUM(s.price-i.cost),0) from sales s, salesperson sp, inventory i where i.vin_num=s.vin_num and i.part_num=s.part_num and s.sp_id=sp.sp_id group by sp.sp_id order by 2 desc) where rownum<11");
        oci_execute($spQuery);

        print "<table bgcolor=F5F5CD cellpadding=10><tr><th> First </th><th> Last </th> <th> Profit </th></tr>";
        while ($sp=oci_fetch_row($spQuery))
        {          
          $nameQuery=oci_parse($conn, "select first, last from salesperson where sp_id=".$sp[0]."");
          oci_execute($nameQuery);
          $name=oci_fetch_row($nameQuery);

          print "<tr><td>".$name[0]."</td><td>".$name[1]."</td><td>$".number_format($sp[1],2)."</td></tr>";
        }

        print "</table></td></tr></table><br>";

        $allSP=oci_parse($conn, "select * from salesperson order by 3, 2");
        oci_execute($allSP);

        print "<table cellpadding=10><tr><td>Lookup by Salesperson</td><td><br><form method=post action=sales.php> <select name=SPID> ";

        while($data=oci_fetch_row($allSP))
        {
          print "<option value=".$data[0]."> ".$data[1]." ".$data[2]."</option>";
        }

        print "</select><input type=hidden name=SPLOOKUP></td><td><input type=submit value='Show Sales'></form></td>";

        $allCust=oci_parse($conn, "select * from customers");
        oci_execute($allCust);

        print "<td> Lookup by Customer </td><td><br><form method=post action=sales.php><select name=CUSTID>";
        while($data=oci_fetch_row($allCust))
        {
          print "<option value=".$data[0]."> ".$data[1]." ".$data[2]."</option>";
        }
        print "</select><input type=hidden name=CUSTLOOKUP></td><td><input type=submit value='Show Sales'></form></td></tr></table>";

        if (array_key_exists('CUSTLOOKUP', $_POST))
        {
          $nameQuery=oci_parse($conn, "select first, last from customers where cust_id=".$_POST['CUSTID']."");
          oci_execute($nameQuery);
          $name=oci_fetch_row($nameQuery);

          print "<h3>Showing data for ".$name[0]." ".$name[1]."</h3>";
          print "<table cellpadding=10 bgcolor=F5F5CD><tr><th> Part Description </th><th> Price </th> <th> Sale Date </th> <th> Payment Method </th><th> Salesperson Name</th></tr>";

          $salesQuery=oci_parse($conn, "select p.part_desc, s.price, s.sale_date, s.pay_method, sp.first, sp.last from sales s, part p, salesperson sp where s.part_num=p.part_num and sp.sp_id=s.sp_id and s.cust_id=".$_POST['CUSTID']." order by 3 desc ");
          oci_execute($salesQuery);

          while ($data=oci_fetch_row($salesQuery))
          {
            print "<tr><td>".$data[0]."</td><td>".$data[1]."</td><td>".$data[2]."</td><td>".$data[3]."</td><td>".$data[4]." ".$data[5]." </td></tr>";
          }
          print "</table>";

        }

        if (array_key_exists('SPLOOKUP', $_POST))
        {
          $nameQuery=oci_parse($conn, "select first, last from salesperson where sp_id=".$_POST['SPID']."");
          oci_execute($nameQuery);
          $name=oci_fetch_row($nameQuery);

          print "<h3>Showing data for ".$name[0]." ".$name[1]."</h3>";
          print "<table cellpadding=10 bgcolor=F5F5CD><tr><th> Part Description </th><th> Price </th> <th> Sale Date </th> <th> Payment Method </th> <th> Customer Name </th></tr>";

          $salesQuery=oci_parse($conn, "select p.part_desc, s.price, s.sale_date, s.pay_method, c.first, c.last from sales s, part p, customers c where s.part_num=p.part_num and s.cust_id=c.cust_id and s.sp_id=".$_POST['SPID']." order by 3 desc ");
          oci_execute($salesQuery);

          while ($data=oci_fetch_row($salesQuery))
          {
            print "<tr><td>".$data[0]."</td><td>".$data[1]."</td><td>".$data[2]."</td><td>".$data[3]."</td><td>".$data[4]." ".$data[5]." </td></tr>";
          }
          print "</table>";

        }

      ?>

    </div>
    </div>
  </body>
</html>