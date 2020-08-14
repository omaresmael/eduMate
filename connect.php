<?php
$dsn = 'mysql:host=localhost;dbname=edumate';
$user = 'root';
$pass = '';
global $con;
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);

try {

    $con = new PDO($dsn, $user, $pass, $option);
    $con ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     //echo 'connect';

}
catch (PDOException $e){
    echo 'failed to connect' . $e ->getMessage();
}
