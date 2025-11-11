<?php 
// registro.php
include('includes/header.php'); 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4">Registro de Nuevo Personal Escolar</h2>
            
            <?php 
            if (isset($_GET['status']) && $_GET['status'] == 'success') {
                echo '<div class="alert alert-success">Usuario registrado con éxito. Ya puedes iniciar sesión.</div>';
            }
            if (isset($_GET['error']) && $_GET['error'] == 'email') {
                echo '<div class="alert alert-danger">Error: El correo electrónico ya está registrado.</div>';
            }
            ?>

            <form action="registrar-usuario.php" method="POST">
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre </label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electronico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol en la Escuela</label>
                    <select class="form-select" id="rol" name="rol" required>
                        <option value="">Seleccione el rol</option>
                        <option value="Docente">Docente / Tutor</option>
                        <option value="Director">Director / Administrativo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100">Registrar</button>
            </form>
            
            <p class="mt-3 text-center"><a href="login.php">¿Ya tienes cuenta? Inicia Sesión</a></p>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>