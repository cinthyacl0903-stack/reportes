<?php
// authenticate.php
session_start();
require_once('config/conection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    $sql = "SELECT id, nombre, password, rol FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        $_SESSION['user_role'] = $user['rol'];
        
        header("Location: lista-reportes.php");
        exit();
        
    } else {
        header("Location: login.php?error=1");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>