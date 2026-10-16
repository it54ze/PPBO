<?php
require_once 'DatabaseConnection.php';

$db = DatabaseConnection::getInstance();
$connection = $db->getConnection();

if ($connection) {
    echo "Berhasil terkoneksi ke database!!!";
}