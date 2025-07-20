<?php
require_once "./controllers/App_configuracionController.php";
$configuracionController = new configuracionController();

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estructura de Tabla: <?php echo htmlspecialchars($tabla); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .requerido { background-color: #D94446; }
        .opcional { background-color: #68BA7F; }
        .info { background-color: #f0f8ff; padding: 10px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
	
    <h1>Estructura de la tabla: <?php echo htmlspecialchars($tabla); ?></h1>
    
    <div class="info">
        <strong>Leyenda:</strong><br>
        <span style="background-color: #D94446; padding: 2px 5px;">Rojo</span> = Campo requerido <br>
        <span style="background-color: #68BA7F; padding: 2px 5px;">Verde</span> = Campo opcional
    </div>
	<form method="POST">
		<label>Consultar otra tabla: <?php echo $resultado; ?></label>
		<input type="text" name="tabla" placeholder="Nombre de la tabla" required>
		<button type="submit">Consultar</button>
	</form>
	<?php
		
	if (isset($_POST['tabla'])) {
		echo $configuracionController->mostrar_estructura_tabla($_POST['tabla']);
	} else {
		echo 'Indique la tabla a consultar';
	}
	
		
	?>

    
    

    
</body>
</html>