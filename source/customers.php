
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
    <b> Customers </b> <br>
        <a href="part.php" >  Part Lookup </a> <br>
            <a href="car.php" > Car Lookup </a> <br>


    </div>
    <div id=textBody>
    <?php
    require("connect.php");
    require_once("funcs.php");

    escapeON();

    if (array_key_exists('UPDATECUST', $_POST))
        {
          if  ( !is_numeric($_POST['NEWZIP']) || !is_numeric($_POST['NEWPHONE']) )
          {
            print "<i> Error: zip code and phone number must be numeric. </i>";
          }

          else {
          $text=sprintf("update customers set first=q'[%s]', last=q'[%s]', street_addr=q'[%s]', city=q'[%s]', state=q'[%s]', zip=%d, phone=%d where cust_id=".$_POST['UPDATECUST']."",  $_POST['NEWFIRST'], $_POST['NEWLAST'], $_POST['NEWADD'], $_POST['NEWCITY'], $_POST['NEWSTATE'], $_POST['NEWZIP'], $_POST['NEWPHONE']);
          $theQueryC=oci_parse($conn, $text);
          oci_execute($theQueryC);
          print "<i>Customer Updated.</i>";
          }
        }

    if (array_key_exists('CREATECUST', $_POST))
        {
        if  ( !is_numeric($_POST['NEWZIP']) || !is_numeric($_POST['NEWPHONE'])  || !is_numeric($_POST['NEWID']) )
          {
            print "<i> Error: zip code and phone number must be numeric. </i>";
          }

          else{
          $isInUse=false;
          $checkQuery=oci_parse($conn, "select cust_id from customers");
          oci_execute($checkQuery);

          while($cid=oci_fetch_row($checkQuery))
          {
            if($cid[0]==$_POST['NEWID'])
            {
              print "Error: ID is already in use.  Please pick a different one.";
              $isInUse=true;
            }
          }

          if (!$isInUse && $_POST['NEWID']!=NULL && $_POST['NEWFIRST']!=NULL && $_POST['NEWLAST']!=NULL && $_POST['NEWADD']!=NULL && $_POST['NEWCITY']!=NULL && $_POST['NEWSTATE']!=NULL && $_POST['NEWZIP']!=NULL && $_POST['NEWPHONE']!=NULL)
          {
            $text2=sprintf("insert into customers values (%d, q'[%s]', q'[%s]', q'[%s]', q'[%s]', q'[%s]', %d, %d) ", $_POST['NEWID'], $_POST['NEWFIRST'], $_POST['NEWLAST'], $_POST['NEWADD'], $_POST['NEWCITY'], $_POST['NEWSTATE'], $_POST['NEWZIP'], $_POST['NEWPHONE']);
            $theQueryD=oci_parse($conn, $text2);
            oci_execute($theQueryD);
            print "<i>Customer Added.</i>";
          }
          else 
          {
            print "<i>Error: fields cannot be left blank.</i>";
          }
        }
      }

      /*if (array_key_exists('DROPCUST', $_POST))
      {
          $theQueryE=oci_parse($conn, "delete from customers where cust_id=".$_POST['DROPCUST']."");
          oci_execute($theQueryE);
          print "<i>Customer Deleted.</i>";
      }  removed because we thought you wouldnt want to delete customers, as this would entail deleting sale records.  These should be kept, even if a customer becomes inactive.  */ 
    ?>

    <h2> Customer Management</h2>
    <p> View and edit information of current customers. </p>

     <?php
        require("connect.php");
        $theQuery=oci_parse($conn, "select * from customers order by 3,2 asc");
        oci_execute($theQuery);

        print "<table><tr><td><form method=post action=customers.php>Choose a Customer <select name=CUSTID>";
        while($name=oci_fetch_row($theQuery))
        {
          $fullName="".$name[1]." ".$name[2];
          print "<option value=".$name[0].">".$fullName."</option>";
        }
        print "</select><input type=hidden name=VIEW><input type=submit value='View and Modify' /></form><td>";

        print " <td><form method=post action=customers.php><input type=hidden name=NEWCUST><input type=submit value='Create New Customer'></form></td></tr></table>";

        if(array_key_exists('VIEW', $_POST))
        {
          $theQueryB=oci_parse($conn, "select * from customers where cust_id=".$_POST['CUSTID']."");
          oci_execute($theQueryB);
          while($info=oci_fetch_row($theQueryB))
          {
          print "<form method=post action=customers.php><table border=0 cellpadding=8, cellspacing=4>";
          print "<tr><td bgcolor=B7C3D0>Customer ID</td><td bgcolor=B7C3D0>".$info[0]."<input type=hidden name=NEWID value='".$info[0]."'></td></tr>";
          print "<tr><td bgcolor=B7C3D0>First Name</td><td bgcolor=B7C3D0><input type=text name=NEWFIRST value='".escapestring($info[1])."'></td></tr>";
          print "<tr><td bgcolor=B7C3D0>Last Name</td><td bgcolor=B7C3D0><input type=text name=NEWLAST value='".escapestring($info[2])."'></td></tr>";
          print "<tr><td bgcolor=B7C3D0>Street Address</td><td bgcolor=B7C3D0><input type=text name=NEWADD value='".escapestring($info[3])."'></td></tr>";
          print "<tr><td bgcolor=B7C3D0>City</td><td bgcolor=B7C3D0><input type=text name=NEWCITY value='".escapestring($info[4])."'></td></tr>";
          print "<tr><td bgcolor=B7C3D0>State</td><td bgcolor=B7C3D0><input type=text name=NEWSTATE value='".escapestring($info[5])."'></td></tr>";
          print "<tr><td bgcolor=B7C3D0>Zip Code</td><td bgcolor=B7C3D0><input type=text name=NEWZIP value='".escapestring($info[6])."'></td></tr>";
          print "<tr><td bgcolor=B7C3D0>Phone</td><td bgcolor=B7C3D0><input type=text name=NEWPHONE value='".escapestring($info[7])."'></td></tr>";
          print "</table><input type=hidden name=UPDATECUST value=".$info[0]."><input type=hidden name=VIEW><input type=hidden name=CUSTID value=".$info[0]."><input type=submit value='Update Info'></form>";
         // print "<form method=post action=customers.php><input type=hidden name=DROPCUST value=".$info[0]."><input type=submit value='Delete Customer'></form>";
          }
        } 
        else if (array_key_exists('NEWCUST', $_POST))
        {
          print "<form method=post action=customers.php><table border=0 cellpadding=8, cellspacing=4>";
          print "<tr><td bgcolor=B7C3D0>Customer ID</td><td bgcolor=B7C3D0><input type=text name=NEWID></td></tr>";
          print "<tr><td bgcolor=B7C3D0>First Name</td><td bgcolor=B7C3D0><input type=text name=NEWFIRST></td></tr>";
          print "<tr><td bgcolor=B7C3D0>Last Name</td><td bgcolor=B7C3D0><input type=text name=NEWLAST ></td></tr>";
          print "<tr><td bgcolor=B7C3D0>Street Address</td><td bgcolor=B7C3D0><input type=text name=NEWADD ></td></tr>";
          print "<tr><td bgcolor=B7C3D0>City</td><td bgcolor=B7C3D0><input type=text name=NEWCITY ></td></tr>";
          print "<tr><td bgcolor=B7C3D0>State</td><td bgcolor=B7C3D0><input type=text name=NEWSTATE ></td></tr>";
          print "<tr><td bgcolor=B7C3D0>Zip Code</td><td bgcolor=B7C3D0><input type=text name=NEWZIP ></td></tr>";
          print "<tr><td bgcolor=B7C3D0>Phone</td><td bgcolor=B7C3D0><input type=text name=NEWPHONE ></td></tr>";
          print "</table><input type=hidden name=CREATECUST><input type=submit value='Create New'></form>";
        } 

    ?>

    </div>
    </div>
  </body>
</html>