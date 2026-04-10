<?php
$host = 'localhost';
$usuario = 'root';
$password = '';
$basedatos = 'toldosdb';
$conn = new mysqli($host, $usuario, $password, $basedatos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>