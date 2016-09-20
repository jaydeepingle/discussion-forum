<?php
//Required Configuration file

//We log to the DataBase
mysql_connect('localhost', 'root', 'root');
mysql_select_db('forum_database');

//Username of the Administrator
$admin='admin';

//Forum Home Page
$url_home = 'index.php';

//Design Name
$design = 'default';

//Initialization
include('init.php');
?>
