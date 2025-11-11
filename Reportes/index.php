<?php include('includes/header.php'); ?>

<div class="container mt-5">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Sistema de Gestión de Incidentes Escolares</h1>
            <p class="col-md-8 fs-4">Plataforma ágil para reportar, seguir y resolver incidentes de seguridad.</p>
            
            <?php 
            if (isset($_GET['status']) && $_GET['status'] == 'success') {
                echo '<div class="alert alert-success" role="alert">¡Reporte enviado con éxito!</div>';
            }
            ?>

            <a href="reporte.php" class="btn btn-danger  btn-lg mt-3">
                Reportar un Incidente Ahora
            </a>
            
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>