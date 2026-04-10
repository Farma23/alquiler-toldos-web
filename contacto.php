<?php
include 'conectar.php';

$mensaje_exito = "";
$errores = [
    'nombre' => "",
    'telefono' => "",
    'mensaje' => ""
];
$nombre = "";
$telefono = "";
$mensaje = "";

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos con seguridad
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $telefono = htmlspecialchars(trim($_POST["telefono"]));
    $mensaje = htmlspecialchars(trim($_POST["mensaje"]));

    // Validación de nombre
    if (empty($nombre)) {
        $errores['nombre'] = "Por favor, ingresa tu nombre.";
    } elseif (strlen($nombre) < 2) {
        $errores['nombre'] = "El nombre es muy corto.";
    }

    // Validación de teléfono
    if (empty($telefono)) {
        $errores['telefono'] = "Por favor, ingresa tu teléfono.";
    } elseif (!preg_match('/^\d{8,}$/', $telefono)) {
        $errores['telefono'] = "El teléfono debe contener solo números y al menos 8 dígitos.";
    }

    // Validación de mensaje
    if (empty($mensaje)) {
        $errores['mensaje'] = "Por favor, escribe tu mensaje.";
    } elseif (strlen($mensaje) < 5) {
        $errores['mensaje'] = "El mensaje es demasiado corto.";
    }

    // Si no hay errores, insertar directamente en contacto
    if (!array_filter($errores)) {
        $stmt = $conn->prepare("INSERT INTO contacto (nombre, telefono, mensaje, fecha) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $nombre, $telefono, $mensaje);

        if ($stmt->execute()) {
            $mensaje_exito = "¡Tu mensaje ha sido enviado con éxito!";
            $nombre = $telefono = $mensaje = "";
        } else {
            $mensaje_exito = "Error al enviar el mensaje. Intenta nuevamente.";
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Contacto | Toldos Aras</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
body { font-family: 'Arial', sans-serif; background-color: #f5f5f5; color: #333; line-height: 1.6; }
.error { color: red; font-size: 0.9em; margin-top: 5px; }
.success-message { color: green; font-weight: bold; margin-top: 10px; text-align: center; }
</style>
</head>
<body>
<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light border-bottom mb-4">
    <a class="navbar-brand brand" href="#">Alquiler de TOLDOS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link fw-bold" href="index.html">Inicio</a></li>
        <li class="nav-item"><a class="nav-link fw-bold" href="servicios.php">Productos y Servicios</a></li>
        <li class="nav-item"><a class="nav-link fw-bold active" aria-current="page" href="contacto.php">Contacto</a></li>
        <li class="nav-item"><a class="nav-link fw-bold" href="acerca-de.html">Acerca de</a></li>
      </ul>
    </div>
  </nav>

  <h1 class="text-center text-primary mb-4">Contacto</h1>

  <div class="bg-white p-4 rounded shadow-sm text-center">
    <?php if ($mensaje_exito): ?>
      <div class="success-message"><?php echo $mensaje_exito; ?></div>
    <?php endif; ?>

    <form method="POST" class="text-start mx-auto" style="max-width:500px;">
      <div class="mb-3">
        <label for="nombre" class="form-label fw-bold">Nombre:</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>" required />
        <?php if ($errores['nombre']): ?>
          <div class="error"><?php echo $errores['nombre']; ?></div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="telefono" class="form-label fw-bold">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($telefono); ?>" required />
        <?php if ($errores['telefono']): ?>
          <div class="error"><?php echo $errores['telefono']; ?></div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="mensaje" class="form-label fw-bold">Mensaje:</label>
        <textarea id="mensaje" name="mensaje" rows="4" class="form-control" required><?php echo htmlspecialchars($mensaje); ?></textarea>
        <?php if ($errores['mensaje']): ?>
          <div class="error"><?php echo $errores['mensaje']; ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3">Enviar</button>
      <a href="https://wa.me/62135619?text=Hola,%20quiero%20más%20información%20sobre%20sus%20toldos" 
         target="_blank" class="btn btn-success w-100">
         📲 Contáctanos por WhatsApp
      </a>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
