<?php
include 'conectar.php';

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$mensaje = $_POST['mensaje'];

// Buscar si ya existe el cliente
$sql_check = "SELECT id FROM clientes WHERE telefono = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("s", $telefono);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($cliente_id);
    $stmt->fetch();
} else {
    // Crear nuevo cliente
    $sql_cliente = "INSERT INTO clientes (nombre, telefono, fecha_registro) VALUES (?, ?, NOW())";
    $stmt_cliente = $conn->prepare($sql_cliente);
    $stmt_cliente->bind_param("ss", $nombre, $telefono);
    $stmt_cliente->execute();
    $cliente_id = $stmt_cliente->insert_id;
}

// Insertar mensaje
$sql_mensaje = "INSERT INTO mensajes (cliente_id, mensaje, fecha_envio) VALUES (?, ?, NOW())";
$stmt_mensaje = $conn->prepare($sql_mensaje);
$stmt_mensaje->bind_param("is", $cliente_id, $mensaje);
$stmt_mensaje->execute();

echo "Mensaje guardado correctamente.";
$conn->close();
?>
