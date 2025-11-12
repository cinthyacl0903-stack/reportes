<?php
// ver-incidente.php
session_start();
require_once __DIR__ . '/config/conection.php';

// Restricción de acceso: Docente o Director
$rol = isset($_SESSION['user_role']) ? strtolower($_SESSION['user_role']) : '';
if (!isset($_SESSION['user_id']) || !in_array($rol, ['docente','director'])) {
  header('Location: login.php'); exit;
}

// Validar parámetro id
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  http_response_code(400);
  die('Solicitud inválida: falta ID.');
}

// Consultar incidente + nombre de quien reportó (si no fue anónimo)
$sql = "
  SELECT i.id, i.titulo, i.tipo, i.estado, i.fecha_reporte, i.descripcion,
         i.usuario_reporta_id,
         CASE WHEN i.usuario_reporta_id IS NULL THEN 'Anónimo' ELSE u.nombre END AS reportado_por
  FROM incidentes i
  LEFT JOIN usuarios u ON i.usuario_reporta_id = u.id
  WHERE i.id = :id
  LIMIT 1
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$incidente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$incidente) {
  http_response_code(404);
  die('Incidente no encontrado.');
}

include('includes/header.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Detalle del Incidente #<?= (int)$incidente['id']; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .card{max-width:900px;margin:24px auto;padding:20px;border:1px solid #e5e7eb;border-radius:14px}
    .muted{color:#6b7280}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    @media (max-width:860px){.row{grid-template-columns:1fr}}
    .badge{display:inline-block;padding:.35rem .6rem;border-radius:.5rem;font-size:.85rem}
    .bg-success{background:#198754;color:#fff}.bg-warning{background:#ffc107;color:#000}.bg-danger{background:#dc3545;color:#fff}.bg-secondary{background:#6c757d;color:#fff}
    a.btn{display:inline-block;padding:10px 14px;border-radius:10px;border:1px solid #e5e7eb;text-decoration:none}
  </style>
</head>
<body>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
    <div>
      <h2 style="margin:0;">Incidente #<?= (int)$incidente['id']; ?></h2>
      <div class="muted">Registrado el <?= htmlspecialchars(date('d/m/Y H:i', strtotime($incidente['fecha_reporte']))); ?></div>
    </div>
    <div>
      <a class="btn" href="lista-reportes.php">← Volver a la lista</a>
    </div>
  </div>

  <hr>

  <div class="row">
    <div>
      <h5 class="muted">Título</h5>
      <p><?= htmlspecialchars($incidente['titulo']); ?></p>
    </div>
    <div>
      <h5 class="muted">Tipo</h5>
      <p><span class="badge bg-secondary"><?= htmlspecialchars($incidente['tipo']); ?></span></p>
    </div>
    <div>
      <h5 class="muted">Estado</h5>
      <p>
        <?php
          $estado = (string)$incidente['estado'];
          $clase = 'bg-danger';
          if ($estado === 'Resuelto') $clase = 'bg-success';
          elseif ($estado === 'En Proceso') $clase = 'bg-warning';
          echo "<span class='badge $clase'>".htmlspecialchars($estado)."</span>";
        ?>
      </p>
    </div>
    <div>
      <h5 class="muted">Reportado por</h5>
      <p><?= htmlspecialchars($incidente['reportado_por']); ?></p>
    </div>
  </div>

  <h5 class="muted" style="margin-top:16px;">Descripción</h5>
  <div style="white-space:pre-wrap; border:1px solid #eee; border-radius:10px; padding:12px; background:#fafafa;">
    <?= nl2br(htmlspecialchars($incidente['descripcion'])); ?>
  </div>
</div>
</body>
</html>
<?php include('includes/footer.php'); ?>
