<?php
// api-estadisticas.php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/config/conection.php'; // $pdo (PDO)

// Esquema real:
$tabla   = 'incidentes';
$colTipo = 'tipo';

/*
 Normalización:
 - 'Bullying'                     -> 'Bullying'
 - 'Robo de objetos' (o 'Robo%')  -> 'Robo'
 - 'Accidente / Riesgo' ('Accidente%')-> 'Accidente'
 - 'Otro' ('Otro%','Otros%')      -> 'Otro'
 - Cualquier otra variante        -> 'Otro'
*/
$sql = "
  SELECT categoria, COUNT(*) AS total
  FROM (
    SELECT
      CASE
        WHEN LOWER(TRIM($colTipo)) LIKE 'bullying%'          THEN 'Bullying'
        WHEN LOWER(TRIM($colTipo)) LIKE 'robo%'              THEN 'Robo'
        WHEN LOWER(TRIM($colTipo)) LIKE 'robo de objetos%'   THEN 'Robo'
        WHEN LOWER(TRIM($colTipo)) LIKE 'accidente%'         THEN 'Accidente'
        WHEN LOWER(TRIM($colTipo)) LIKE 'accidente / riesgo%'THEN 'Accidente'
        WHEN LOWER(TRIM($colTipo)) LIKE 'otro%'              THEN 'Otro'
        WHEN LOWER(TRIM($colTipo)) LIKE 'otros%'             THEN 'Otro'
        ELSE 'Otro'
      END AS categoria
    FROM $tabla
  ) t
  GROUP BY categoria
  ORDER BY total DESC
";

$stmt = $pdo->query($sql);

$labels = [];
$totales = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $labels[]  = $row['categoria'];
  $totales[] = (int)$row['total'];
}

$totalGeneral = array_sum($totales);
$porcentajes = [];
if ($totalGeneral > 0) {
  foreach ($totales as $t) {
    $porcentajes[] = round(($t * 100) / $totalGeneral, 2);
  }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'labels'       => $labels,
  'totales'      => $totales,
  'porcentajes'  => $porcentajes,
  'totalGeneral' => $totalGeneral
], JSON_UNESCAPED_UNICODE);
