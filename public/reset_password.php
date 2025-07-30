<?php
    $token = $_GET['token'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title></head>
<body>
<h2>Restablecer Contraseña</h2>
<form action="auth.php" method="POST">
    <input type="hidden" name="action" value="reset_confirm">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="password" name="password" placeholder="Nueva contraseña" required><br>
    <input type="password" name="password2" placeholder="Repetir contraseña" required><br>
    <button type="submit">Actualizar</button>
</form>
</body>
</html>

