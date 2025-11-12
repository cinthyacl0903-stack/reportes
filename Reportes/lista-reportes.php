<?php
// lista_incidentes.php
session_start();
require_once('config/conection.php');

// RESTRICCIÓN DE ACCESO: Solo Docente y Director
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'Docente' && $_SESSION['user_role'] != 'Director')) {
    header("Location: login.php");
    exit();
}

include('includes/header.php');

// Consulta SQL con LEFT JOIN para obtener el nombre del reportante (si no es anónimo)
$sql = "SELECT i.id, i.titulo, i.tipo, i.estado, i.fecha_reporte, 
               CASE WHEN i.usuario_reporta_id IS NULL THEN 'Anónimo' ELSE u.nombre END as reportado_por 
        FROM incidentes i
        LEFT JOIN usuarios u ON i.usuario_reporta_id = u.id
        ORDER BY i.fecha_reporte DESC";

$incidentes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="mb-4"> Incidentes Reportados (Rol: <?= $_SESSION['user_role'] ?>)</h2>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Fecha Reporte</th>
                <th>Reportado Por</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($incidentes as $incidente): ?>
                <tr>
                    <td><?= htmlspecialchars($incidente['id']); ?></td>
                    <td><?= htmlspecialchars($incidente['titulo']); ?></td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($incidente['tipo']); ?></span></td>
                    <td>
                        <?php
                        $estado = htmlspecialchars($incidente['estado']);
                        $clase = match ($estado) {
                            'Resuelto' => 'success',
                            'En Proceso' => 'warning',
                            default => 'danger',
                        };
                        echo "<span class='badge bg-$clase'>$estado</span>";
                        ?>
                    </td>
                    <td><?= date("d/m/Y H:i", strtotime($incidente['fecha_reporte'])); ?></td>
                    <td><?= htmlspecialchars($incidente['reportado_por']); ?></td>
                    <td>
                        <a href="ver-incidente.php?id=<?= (int)$incidente['id']; ?>" class="btn btn-sm btn-info">
                            Ver detalle
                        </a>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>