<?php

if (isset($_GET['concepto']) && isset($_GET['fecha']) && isset($_GET['importe']) && isset($_GET['id'])) {
    $concepto = $_GET['concepto'];
    $fecha = $_GET['fecha'];
    $importe = $_GET['importe'];
    $id = $_GET['id'];
    
    // Mostrar el formulario de edición con los valores predefinidos
    echo "<form action='index.php?action=editarRegistro' method='post'>";
    echo "<input type='hidden' name='concepto' value='$concepto'>";
    echo "<input type='hidden' name='fecha' value='$fecha'>";
    echo "<input type='hidden' name='importe' value='$importe'>";
    
    echo "<label for='nuevoConcepto'>Nuevo Concepto:</label>";
    echo "<input type='text' name='nuevoConcepto' value='$concepto'>";
    
    echo "<label for='nuevaFecha'>Nueva Fecha:</label>";
    echo "<input type='text' name='nuevaFecha' value='$fecha'>";
    
    echo "<label for='nuevoImporte'>Nuevo Importe:</label>";
    echo "<input type='text' name='nuevoImporte' value='$importe'>";
    echo "<input type='hidden' name='id' value= '$id'>";
    
    echo "<input type='submit' value='Guardar Cambios'>";
    echo "</form>";
} else {
    echo "Los parámetros de registro no se proporcionaron correctamente.";
}
 if (isset($errores)): ?>
    <ul style="color: red;">
        <?php foreach ($errores as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

