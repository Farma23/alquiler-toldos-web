<?php
include 'conectar.php';

// Número de WhatsApp con código de país (sin +). Ej: Costa Rica -> 506XXXXXXXX
$WHATSAPP_NUM = '62135619';

// Cargar usos/categorías para filtros
$usosResult = $conn->query("SELECT DISTINCT uso_sugerido FROM productos WHERE disponible = 1 ORDER BY uso_sugerido");

// Cargar productos
$result = $conn->query("SELECT * FROM productos WHERE disponible = 1");

// Helper simple para slug de uso_sugerido (para data-attributes)
function uso_slug($s) {
  $s = strtolower(trim($s ?: 'general'));
  $s = iconv('UTF-8','ASCII//TRANSLIT',$s);
  $s = preg_replace('/[^a-z0-9]+/','-',$s);
  return trim($s,'-');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Productos y Servicios | Toldos Aras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body{font-family:Arial,sans-serif;background:#f5f5f5;color:#333;line-height:1.6;}
    .brand{font-size:1.5em;font-weight:bold;color:#2c3e50;}
    .card{margin-bottom:20px;}
    .filters .btn{margin:0 .25rem .5rem 0;}
    .tagline{font-style:italic;margin-top:30px;text-align:center}
    .badge-uso{background:#eef7ff;color:#0d6efd;}
  </style>
</head>
<body>
<div class="container">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light border-bottom mb-4">
    <a class="navbar-brand brand" href="#">Alquiler de TOLDOS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link fw-bold" href="index.html">Inicio</a></li>
        <li class="nav-item"><a class="nav-link fw-bold active" aria-current="page" href="servicios.php">Productos y Servicios</a></li>
        <li class="nav-item"><a class="nav-link fw-bold" href="contacto.php">Contacto</a></li>
        <li class="nav-item"><a class="nav-link fw-bold" href="acerca-de.html">Acerca de</a></li>
      </ul>
    </div>
  </nav>

  <h1 class="text-center text-primary mb-3">Alquiler de TOLDOS</h1>

  <!-- Filtros por uso sugerido -->
  <div class="bg-white p-3 rounded shadow-sm mb-3">
    <div class="row align-items-center">
      <div class="col-md-8">
        <div class="filters">
          <button class="btn btn-sm btn-outline-primary active" data-filter="all">Todos</button>
          <?php while ($u = $usosResult->fetch_assoc()): 
            $uso = $u['uso_sugerido'] ?: 'General'; ?>
            <button class="btn btn-sm btn-outline-primary" data-filter="<?php echo uso_slug($uso); ?>">
              <?php echo htmlspecialchars($uso); ?>
            </button>
          <?php endwhile; ?>
        </div>
      </div>
      <div class="col-md-4">
        <input id="search" type="text" class="form-control form-control-sm" placeholder="Buscar por nombre, material o dimensiones...">
      </div>
    </div>
  </div>

  <!-- Listado de productos -->
  <div class="bg-white p-4 rounded shadow-sm">
    <div class="row" id="productosGrid">
      <?php if ($result && $result->num_rows): ?>
        <?php while ($row = $result->fetch_assoc()): 
          $nombre = $row['nombre'];
          $dim = $row['dimensiones'];
          $mat = $row['material'];
          $usoTxt = $row['uso_sugerido'] ?: 'General';
          $usoData = uso_slug($usoTxt);
          $img = $row['imagen'] ?: 'imagenes/placeholder.jpg';

          // Mensaje prellenado para WhatsApp
          $waText = rawurlencode("Hola, quiero reservar el {$nombre} ({$dim}) para mi evento. ¿Disponibilidad y precio, por favor?");
          $waHref = "https://wa.me/{$WHATSAPP_NUM}?text={$waText}";
        ?>
        <div class="col-md-4 producto" 
             data-uso="<?php echo $usoData; ?>"
             data-name="<?php echo strtolower(htmlspecialchars($nombre)); ?>"
             data-material="<?php echo strtolower(htmlspecialchars($mat)); ?>"
             data-dimension="<?php echo strtolower(htmlspecialchars($dim)); ?>">
          <div class="card shadow-sm h-100">
            <img src="<?php echo htmlspecialchars($img); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($nombre); ?>">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title mb-1"><?php echo htmlspecialchars($nombre); ?></h5>
              <span class="badge badge-uso rounded-pill px-3 py-1 mb-2"><?php echo htmlspecialchars($usoTxt); ?></span>
              <p class="card-text mb-2">
                <b>Dimensiones:</b> <?php echo htmlspecialchars($dim); ?><br>
                <b>Material:</b> <?php echo htmlspecialchars($mat); ?><br>
                <b>Colores:</b> <?php echo isset($row['colores']) && $row['colores'] !== '' ? htmlspecialchars($row['colores']) : 'Consultar'; ?><br>
                ✅ Incluye montaje y desmontaje<br>
                🌦 Protege contra sol, lluvia y viento
              </p>
              <div class="mt-auto">
                <a href="contacto.php?producto=<?php echo urlencode($nombre); ?>" class="btn btn-primary w-100 mb-2">
                  Reservar ahora
                </a>
                <a href="<?php echo $waHref; ?>" target="_blank" class="btn btn-success w-100">
                  📲 Consultar por WhatsApp
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted">No hay productos disponibles por el momento.</div>
      <?php endif; ?>
    </div>

    <div class="tagline">
      "Personalizamos cada detalle para que tu evento sea único"
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Filtro por uso
document.querySelectorAll('.filters [data-filter]').forEach(btn=>{
  btn.addEventListener('click',()=>{
    document.querySelectorAll('.filters .btn').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    const filtro = btn.getAttribute('data-filter');
    document.querySelectorAll('#productosGrid .producto').forEach(card=>{
      const uso = card.getAttribute('data-uso');
      card.style.display = (filtro === 'all' || uso === filtro) ? '' : 'none';
    });
  });
});

// Búsqueda rápida
const search = document.getElementById('search');
search.addEventListener('input', ()=>{
  const q = search.value.toLowerCase().trim();
  document.querySelectorAll('#productosGrid .producto').forEach(card=>{
    const text = (
      card.getAttribute('data-name') + ' ' +
      card.getAttribute('data-material') + ' ' +
      card.getAttribute('data-dimension')
    );
    card.style.display = text.includes(q) ? '' : 'none';
  });
});
</script>
</body>
</html>
<?php $conn->close(); ?>
