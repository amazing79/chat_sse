<?php
    $url = htmlspecialchars($_GET['url']) ?? '';
    $type = htmlspecialchars($_GET['type']) ?? '';
    $message = htmlspecialchars($_GET['msg']) ?? '';
    $cartel = $type !== "error" ? "Operación exitosa" : "Oh no, ha ocurrido un error";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informacion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<h1 class="title_app">Mini Chat - PHP</h1>
<div class="container">
    <section style="display: flex;flex-direction: column;gap: 1rem; align-items: center">
        <h2>Resultado operaci&oacute;n</h2>
        <h3><?php echo $cartel; ?></h3>
        <p><?php echo $message; ?></p>
        <p><a href='<?php echo $url; ?>'>Volver</a>
    </section>
</div>
</body>
</html>
