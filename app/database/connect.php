<?php
$driver = 'mysql';
$host = '127.0.0.1';
$host = '127.0.0.1';
$port = '3306';
$db_name = 'p-332541_kemel-urpaq';
$db_user = 'p-332541_admin';
$db_pass = 'Dhtvtyysq1';
$charset = 'utf8';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=$charset", $db_user, $db_pass, $options);
} catch (PDOException $i) {
    die("ошибка подключения к базе данных");
}
?>