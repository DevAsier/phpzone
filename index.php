<?php
session_start();

// Inicializar contador de intentos
if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
}

// Credenciales válidas
$VALID_USER = 'admin';
$VALID_EMAIL = 'asier22acv@gmail.com';
$VALID_PASS = 'Abaioquiena66;';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['usuario'] ?? '');
    $pass = $_POST['password'] ?? '';
    $mail = trim($_POST['email'] ?? '');

    // Comprobación correcta: usuario OR email deben coincidir y la contraseña debe ser correcta
    $userOk  = ($user !== '' && $user === $VALID_USER);
    $emailOk = ($mail !== '' && strtolower($mail) === strtolower($VALID_EMAIL));
    $passOk  = ($pass === $VALID_PASS);

    if (($userOk || $emailOk) && $passOk) {
        $_SESSION['usuario'] = $userOk ? $user : $VALID_USER;
        $_SESSION['mail'] = $emailOk ? $mail : $VALID_EMAIL;
        $_SESSION['intentos'] = 0; // Reiniciar intentos al loguearse
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['intentos']++;
        $restantes = 3 - $_SESSION['intentos'];

        if ($restantes <= 0) {
            $error = "Has superado el número máximo de intentos. Acceso bloqueado.";
        } else {
            $error = "Usuario o contraseña incorrectos. Intentos restantes: $restantes.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - PHPZone</title>
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body>
    <div class="login-container">
        <h2>PHPZone</h2>

        <?php if ($_SESSION['intentos'] < 3): ?>
        <form method="POST" autocomplete="off">
            <input type="text" name="usuario" placeholder="Usuario">

            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <div style="text-align:left; width:90%; margin:5px auto 0;">
              <label style="font-size:13px; color:#666;">
                <input type="checkbox" id="togglePass"> Mostrar contraseña
              </label>
            </div>
            <button type="submit">Entrar</button>
        </form>
        <?php else: ?>
            <p class="error">Tu acceso ha sido bloqueado temporalmente.</p>
        <?php endif; ?>

        <?php if (!empty($error)) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>
    </div>

    <script src="public/js/main.js"></script>
</body>
</html>
