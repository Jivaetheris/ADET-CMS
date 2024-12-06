<?php
$connection = new mysqli('localhost', 'root', '', 'clinicsystem');
if ($connection->connect_error) {
    die('Connection Failed: ' . $connection->connect_error);
}
?>
