<?php
define('DB_SERVER',"localhost");
define('DB_USERNAME',"root");
define('DB_PASSWORD',"");
define('DB_NAME',"login");

//create connection
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

//Check connection
if($link === FALSE){
    die("Error: Cannot connect.. " .mysqli_connect_err());
}
?>