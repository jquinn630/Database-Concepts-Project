<?php

    require("connect.php");

      function escapestring ($string) {
        return str_replace("'", "&#39;", $string);
      }

      function escapeON () {
      	  $theQueryC=oci_parse($conn, "set escape ON");
          oci_execute($theQueryC);
      }

?>