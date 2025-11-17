<?php

if($open_connect != 1){
    die(header('location: form_login.php'));
}

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'swkj_canteen';
$port = Null;
$socket = NULL;
$connect = mysqli_connect($hostname, $username, $password, $database);

if(!$connect) 
    {
die("การเชื่อมต่อข้อมูลล้มเหลว : " . mysqli_connect_error($connect));
}else{
    mysqli_set_charset($connect, 'utf8');
}

?>