<?php
// registrar_usuario.php
require_once('config/conection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Recolección y saneamiento de datos
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password_clara = $_POST['password'];
    $rol = htmlspecialchars(trim($_POST['rol']));

    // 2. Cifrado de la contraseña
    $password_hash = password_hash($password_clara, PASSWORD_DEFAULT);

    try {
        // Verificar si el email ya existe (evita duplicados)
        $check_sql = "SELECT id FROM usuarios WHERE email = :email";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute(['email' => $email]);
        
        if ($check_stmt->rowCount() > 0) {
            // El email ya existe
            header("Location: registro.php?error=email");
            exit();
        }

        // 3. Preparación de la consulta SQL para inserción
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) 
                VALUES (:nombre, :email, :password, :rol)";
        
        $stmt = $pdo->prepare($sql);
        
        // 4. Ejecución de la consulta
        $stmt->execute([
            'nombre' => $nombre,
            'email' => $email,
            'password' => $password_hash,
            'rol' => $rol
        ]);

        // 5. Redirección al formulario de login con mensaje de éxito
        header("Location: login.php?status=success");
        exit();

    } catch (PDOException $e) {
        // En caso de error de la DB
        die("Error al registrar el usuario: " . $e->getMessage());
    }
} else {
    // Si se accede directamente, redireccionar al formulario
    header("Location: registro.php");
    exit();
}
?>