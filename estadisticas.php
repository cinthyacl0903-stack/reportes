<?php
// estadisticas.php
session_start();

// Restringe a Director (ajusta si quieres permitir Docente también)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Director') {
  header('Location: login.php'); exit;
}

include('includes/header.php'); // mantiene tu layout
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Estadísticas de Incidentes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .card { max-width: 980px; margin: 24px auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 14px; }
    h1 { margin: 0 0 6px; font-size: 22px; }
    .sub { color:#6b7280; margin-bottom: 18px; }
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    @media (max-width: 860px){ .grid{ grid-template-columns: 1fr; } }
    ul { line-height: 1.6; padding-left: 18px; }
    .muted { color:#6b7280; font-size: 14px; }
    a.btn { display:inline-block; padding:10px 14px; border-radius:10px; border:1px solid #e5e7eb; text-decoration:none; }
  </style>
</head>
<body>
  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
      <div>
        <h1>Estadísticas de reportes por tipo</h1>
        <div class="sub muted">Vista para Dirección</div>
      </div>
      <div>
        <a href="lista-reportes.php" class="btn">← Volver a la lista</a>
      </div>
    </div>

    <div class="grid">
      <div>
        <canvas id="grafica"></canvas>
      </div>
      <div>
        <h3>Resumen</h3>
        <ul id="lista"></ul>
        <p class="muted" id="total"></p>
      </div>
    </div>
  </div>

<script>
async function cargar() {
  const r = await fetch('api-estadisticas.php', { cache: 'no-store' });
  const data = await r.json();

  // Resumen
  const ul = document.getElementById('lista');
  ul.innerHTML = '';
  if ((data.totalGeneral ?? 0) === 0) {
    const li = document.createElement('li');
    li.textContent = 'Sin datos aún.';
    ul.appendChild(li);
  } else {
    data.labels.forEach((lbl, i) => {
      const tot = data.totales[i] ?? 0;
      const pct = data.porcentajes[i] ?? 0;
      const li = document.createElement('li');
      li.textContent = `${lbl}: ${pct}% (${tot} reportes)`;
      ul.appendChild(li);
    });
  }
  document.getElementById('total').textContent = `Total general: ${data.totalGeneral || 0}`;

  // Gráfica
  new Chart(document.getElementById('grafica'), {
    type: 'doughnut',
    data: { labels: data.labels, datasets: [{ data: data.totales }] },
    options: {
      plugins: {
        legend: { position: 'bottom' },
        tooltip: {
          callbacks: {
            label: (ctx) => {
              const val = ctx.raw ?? 0;
              const total = data.totalGeneral || 1;
              const pct = Math.round((val * 100 / total) * 100) / 100;
              return `${ctx.label}: ${val} (${pct}%)`;
            }
          }
        }
      }
    }
  });
}
cargar();
</script>
</body>
</html>
<?php include('includes/footer.php'); ?>
