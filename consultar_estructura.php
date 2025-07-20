<?php
/**
 * SCRIPT INDEPENDIENTE PARA CONSULTAR ESTRUCTURA DE TABLAS
 * Guarda este archivo como "consultar_estructura.php" en tu raíz
 * Accede desde: http://tu-sitio.com/consultar_estructura.php?tabla=App_empresa_empresa
 */

// Incluir configuración
require_once "./core/configGeneral.php";
require_once "./core/configAPP.php";

// Verificar que el usuario esté logueado (opcional)
session_start(['name'=>SESION]);
/*if (!isset($_SESSION['sesionactiva']) || $_SESSION['sesionactiva'] != true) {
    die("Acceso denegado. Debes estar logueado.");
}*/

// Obtener nombre de tabla desde GET
$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : 'App_empresa_empresa';

try {
    // Conexión a la base de datos
    $pdo = new PDO(SGBD, USER, PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Consultar estructura
    $sql = "SELECT 
                COLUMN_NAME,
                DATA_TYPE,
                CHARACTER_MAXIMUM_LENGTH,
                NUMERIC_PRECISION,
                IS_NULLABLE,
                COLUMN_DEFAULT,
                COLUMN_TYPE,
                COLUMN_KEY,
                EXTRA
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = ?
            ORDER BY ORDINAL_POSITION";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tabla]);
    $estructura = $stmt->fetchAll();
    
    if(empty($estructura)) {
        die("La tabla '$tabla' no existe o no se encontró.");
    }
    
} catch(Exception $e) {
    die("Error: " . $e->getMessage());
}
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
        .requerido { background-color: #ffe6e6; }
        .opcional { background-color: #e6ffe6; }
        .info { background-color: #f0f8ff; padding: 10px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Estructura de la tabla: <?php echo htmlspecialchars($tabla); ?></h1>
    
    <div class="info">
        <strong>Leyenda:</strong>
        <span style="background-color: #ffe6e6; padding: 2px 5px;">Rojo</span> = Campo requerido |
        <span style="background-color: #e6ffe6; padding: 2px 5px;">Verde</span> = Campo opcional
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Campo</th>
                <th>Tipo de Dato</th>
                <th>Max Caracteres</th>
                <th>¿Requerido?</th>
                <th>Valor por Defecto</th>
                <th>Clave</th>
                <th>Extra</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($estructura as $campo): ?>
                <?php 
                $es_requerido = ($campo['IS_NULLABLE'] == 'NO' && $campo['COLUMN_DEFAULT'] === null);
                $clase = $es_requerido ? 'requerido' : 'opcional';
                ?>
                <tr class="<?php echo $clase; ?>">
                    <td><strong><?php echo $campo['COLUMN_NAME']; ?></strong></td>
                    <td><?php echo $campo['COLUMN_TYPE']; ?></td>
                    <td><?php echo $campo['CHARACTER_MAXIMUM_LENGTH'] ?: ($campo['NUMERIC_PRECISION'] ?: 'N/A'); ?></td>
                    <td><?php echo $es_requerido ? 'SÍ' : 'NO'; ?></td>
                    <td><?php echo $campo['COLUMN_DEFAULT'] ?: 'NULL'; ?></td>
                    <td><?php echo $campo['COLUMN_KEY'] ?: '-'; ?></td>
                    <td><?php echo $campo['EXTRA'] ?: '-'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <h3>Reglas de Validación Sugeridas (PHP):</h3>
    <pre style="background-color: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto;">
$reglas_validacion = [
<?php foreach($estructura as $campo): ?>
    <?php if(in_array(strtolower($campo['COLUMN_NAME']), ['empresaid', 'empresacodigo'])) continue; ?>
    '<?php echo strtolower($campo['COLUMN_NAME']); ?>' => [
        <?php if($campo['IS_NULLABLE'] == 'NO' && $campo['COLUMN_DEFAULT'] === null): ?>'requerido' => true,
        <?php endif; ?>
        <?php if($campo['CHARACTER_MAXIMUM_LENGTH']): ?>'max_caracteres' => <?php echo $campo['CHARACTER_MAXIMUM_LENGTH']; ?>,
        <?php endif; ?>
        <?php if(in_array($campo['DATA_TYPE'], ['int', 'bigint', 'smallint'])): ?>'solo_numeros' => true,
        <?php endif; ?>
        <?php if(strpos(strtolower($campo['COLUMN_NAME']), 'email') !== false): ?>'email' => true,
        <?php endif; ?>
    ],
<?php endforeach; ?>
];
    </pre>
    
    <p><a href="?tabla=">← Cambiar tabla</a></p>
    
    <?php if(!isset($_GET['tabla'])): ?>
    <form method="GET">
        <label>Consultar otra tabla:</label>
        <input type="text" name="tabla" placeholder="Nombre de la tabla" required>
        <button type="submit">Consultar</button>
    </form>
    <?php endif; ?>
</body>
</html>