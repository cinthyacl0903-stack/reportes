<?php include('includes/header.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">🚨 Reporte Rápido de Incidente de Seguridad</h2>
            
            <form action="guardar-reporte.php" method="POST">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título Breve del Incidente</label>
                    <input type="text" class="form-control" name="titulo" required>
                </div>

                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Incidente</label>
                    <select class="form-select" name="tipo" required>
                        <option value="">Seleccione el tipo</option>
                        <option value="Bullying">Bullying</option>
                        <option value="Robo">Robo de objetos</option>
                        <option value="Accidente">Accidente / Riesgo</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción Detallada</label>
                    <textarea class="form-control" name="descripcion" rows="4" required></textarea>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="anonimo" value="1">
                    <label class="form-check-label" for="anonimo">Deseo reportar de forma anónima</label>
                </div>

                <button type="submit" class="btn btn-danger btn-lg">Enviar Reporte</button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>