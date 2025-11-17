<?php
$open_connect = 1;

require('connect.php');

if(isset($_POST['email_account']) && isset($_POST['password_account'])){

    $email_account = htmlspecialchars (mysqli_real_escape_string($connect, $_POST['email_account']));
    $password_account = htmlspecialchars (mysqli_real_escape_string($connect, $_POST['password_account']));
    $query_check_account = "SELECT * FROM account where email_account = '$email_account' " ; 
    $call_back_check_account = mysqli_query($connect, $query_check_account);
    if(mysqli_num_rows($call_back_check_account) ==1) {
$result_check_account = mysqli_fetch_assoc($call_back_check_account);
$hash = $result_check_account ['password_account'];
$password_account = $password_account . $result_check_account['salt_account'];

    }else{
die(header('location: form-login.php'));
    }



}else{
    die(header('location: form-login.php'));
}

?>
