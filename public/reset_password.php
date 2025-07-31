<?php
    $token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<h1 class="title_app">Mini Chat - PHP</h1>
<div class="container">
    <form id="reset_password" action="auth.php" method="POST">
        <h2>Restablecer Contraseña</h2>
        <input type="hidden" name="action" value="reset_confirm">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="password" name="password" placeholder="Nueva contraseña" required><br>
        <input type="password" name="password2" placeholder="Repetir contraseña" required><br>
        <button type="submit">Actualizar</button>
    </form>
</div>
</body>
</html>
