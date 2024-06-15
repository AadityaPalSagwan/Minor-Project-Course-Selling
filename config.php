<?php
/*
this file contain database configuration assuming you are running mysql using "root" and password ""
*/

define('db_server', 'localhost');
define('db_email', 'root');
define('db_password', '');
define('db_name', 'intern');

// try connecting to the database 

$conn =  mysqli_connect(db_server, db_email, db_password, db_name);

// check the connection 


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


?>
<!-- if($conn == false){
    dir('Error:cannot connect');
} -->