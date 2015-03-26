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
    <a href="part.php" >  Part Lookup </a> <br>
        <a href="car.php" > Car Lookup </a> <br>

    </div>
    <div id=textBody>
      <h2> Make a Sale </h2>
     <?php

        require("connect.php");
        if (array_key_exists('TRANS', $_POST))
        {
          $spQuery=oci_parse($conn, "select sp_id, first, last from salesperson where sp_id='".$_POST['SPID']."'");
          oci_execute($spQuery);

          $spdata=oci_fetch_row($spQuery);

          if ($spdata[0]==NULL)
          {
            print "Error: Salesperson ID is not recognized.  Please enter a valid number.";
          }
          else 
          {
            print "<table cellspacing=5 cellpadding=5><tr><td bgcolor=F5F5DC>SPID: ".$spdata[0]." </td><td bgcolor=F5F5DC> ".$spdata[1]." </td> <td bgcolor=F5F5DC> ".$spdata[2]." </td></tr>";
            print "<form method=post action=purchase.php><tr><td bgcolor=F5F5DC colspan=2> Enter Customer ID: </td> <td bgcolor=F5F5DC align=center> <input type=text name=CUSTID> </td> </tr>"; 
            print "<tr><td bgcolor=F5F5DC colspan=2> Enter Price: </td> <td bgcolor=F5F5DC align=center> <input type=text name=PRICE> </td> </tr>"; 
            print "<tr><td bgcolor=F5F5DC colspan=2> Select Payment Method</td> <td bgcolor=F5F5DC><select name=PAYMETHOD> <option value=CASH> Cash </option> <option value=CREDIT> Credit </option> <option value=CHECK> Check </option> </select> </td></tr>";
            print "<tr><td bgcolor=F5F5DC colspan=3 align=center> <input type=hidden name=CONFIRMPURCHASE> <input type=hidden name=PARTNUM value='".$_POST['PARTNUM']."'> <input type=hidden name=VINNUM value='".$_POST['VINNUM']."'> <input type=hidden name=SPID value=".$spdata[0]." ><input type=submit value='Confirm Transaction'> </td> </tr> </form> </table>";
          }
        }


       if (array_key_exists('CONFIRMPURCHASE',$_POST))
        {
          $custQuery=oci_parse($conn, "select first, last from customers where cust_id='".$_POST['CUSTID']."'");
          oci_execute($custQuery);
          $customer=oci_fetch_row($custQuery);

        if ($customer[0]==NULL)
        {
          print "Error: there is no customer with the given ID.";
        }

        elseif ($_POST['PRICE']==NULL)
        {
          print "Error: please enter a price.";
        }


        else {
          $trQuery=oci_parse($conn, "select i.vin_num, c.make, c.model, p.part_desc, i.cost from inventory i, part p, car c where i.vin_num=c.vin_num and i.part_num=p.part_num and i.part_num=".$_POST['PARTNUM']." and i.vin_num='".$_POST['VINNUM']."'"); 
          oci_execute($trQuery);
          $info=oci_fetch_row($trQuery);

           print "<i> Please confirm this transaction. </i>";
           print "<table cellspacing=5 cellpadding=5 bgcolor=F5F5DC>";
           print "<tr><td> VIN Number </td> <td> ".$info[0]." </td> </tr>";
           print "<tr><td> Car Make </td> <td>  ".$info[1]." </td> </tr>";
           print "<tr><td> Car Model </td> <td> ".$info[2]."</td> </tr>";
           print "<tr><td> Part Description</td> <td> ".$info[3]." </td> </tr>";
           print "<tr><td> Customer Name</td> <td> ".$customer[0]." ".$customer[1]." </td> </tr>";
           print "<tr><td> Pay Method </td> <td> ".$_POST['PAYMETHOD']." </td> </tr>";
           print "<tr><td> Original Cost </td> <td> ".$info[4]." </td> </tr>";
           print "<tr><td> Price </td> <td> ".$_POST['PRICE']." </td> </tr>";
           print "<tr><td colspan=2 align=center> <form method=post action=purchase.php><input type=hidden name=VINNUM value=".$info[0]."><input type=hidden name=PARTNUM value=".$_POST['PARTNUM']."><input type=hidden name=CUSTID value=".$_POST['CUSTID']."><input type=hidden name=SPID value=".$_POST['SPID'].">";
           print "<input type=hidden name=PRICE value=".$_POST['PRICE']."> <input type=hidden name=PAYMETHOD value=".$_POST['PAYMETHOD']."><input type=hidden name=FINALIZE> <input type=submit value='Finalize'>  </form> </td> </tr></table>";
          }
        } 


        if (array_key_exists('FINALIZE', $_POST))
        {
          $saleQuery=oci_parse($conn, "insert into sales values (".$_POST['CUSTID'].", ".$_POST['SPID'].", q'[".$_POST['VINNUM']."]', ".$_POST['PARTNUM'].", ".$_POST['PRICE'].", (select sysdate from dual), '".$_POST['PAYMETHOD']."' ) ");
          oci_execute($saleQuery);
          $invQuery=oci_parse($conn, "update inventory set in_stock=0 where vin_num=q'[".$_POST['VINNUM']."]' and part_num=".$_POST['PARTNUM']."");
          oci_execute($invQuery);

          print "<i> Transaction finalized </i><br>";
          print "<a class=bodyLink href='inventory.php'> Go back to Inventory </a>";
        }
    ?>

    </div>
    </div>
  </body>
</html>