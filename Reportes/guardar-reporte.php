<?php
// guardar_incidente.php
session_start();
require_once('config/conection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $tipo = htmlspecialchars(trim($_POST['tipo']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    $is_anonimo = isset($_POST['anonimo']);
    
    // Si NO es anónimo Y está logueado, se usa el ID de sesión, sino es NULL (anónimo)
    $usuario_id = (!$is_anonimo && isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : null;

    $sql = "INSERT INTO incidentes (titulo, descripcion, tipo, estado, fecha_reporte, usuario_reporta_id) 
            VALUES (:titulo, :descripcion, :tipo, 'Reportado', NOW(), :usuario_id)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'tipo' => $tipo,
            'usuario_id' => $usuario_id
        ]);

        header("Location: index.php?status=success");
        exit();

    } catch (PDOException $e) {
        die("Error al guardar el incidente: " . $e->getMessage());
    }
} else {
    header("Location: reporte.php");
    exit();
}
?>