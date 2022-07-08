<?php

$host= "localhost";
$user="root";
$password = "";
$dbname = "todo_app";

//  definir le DSN 
$dsn = "mysql:host=$host;dbname=$dbname";

// creer une instance de PDO
try {
$pdo= new PDO($dsn, $user, $password);
} catch(Exception $e) {
echo "exception message:" . $e->getMessage();
}
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


