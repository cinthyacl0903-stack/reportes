<?php include('includes/header.php'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4">🔑 Acceso para Docentes y Directivos</h2>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">Email o contraseña incorrectos.</div>
            <?php endif; ?>

            <form action="autenticar.php" method="POST">
                <div class="mb-3"><label class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" name="email" required></div>
                <div class="mb-3"><label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" required></div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100">Iniciar Sesión</button>
                <p class="mt-3 text-center">
                    ¿Eres nuevo personal? <a href="registro.php">Regístrate aquí</a>
                </p>
            </form>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>