<?php
// connects to the DB

   $host        = "host = 172.16.135.141";
   $port        = "port = 5432";
   $dbname      = "dbname = project";
   $credentials = "user = postgres password=harshal";

   $db = pg_connect( "$host $port $dbname $credentials"  );
   if(!$db) {
    //  echo "Error : Unable to open database\n";
   } else {
     // echo "Opened database successfully\n";
   }
?>
