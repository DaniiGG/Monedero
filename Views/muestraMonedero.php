<!DOCTYPE html>
<html>
<head>
    <title>Monedero</title>
    
    <link rel="stylesheet" type="text/css" href="CSS/muestraMonedero.css">
</head>
<body>
    
<h1>Mi Monedero</h1>
    <div>
    <h1 id="transac"><img height="27" width="27" src="img/4564330.png"> &nbsp; &nbsp; &nbsp;Transacciones &nbsp; &nbsp; &nbsp;<img height="27" width="27" src="img/4564330.png"></h1>
    <table >
        
            <tr>
            <th><a href="index.php?action=ordenarPorConcepto">Concepto (Asc)</a></th>
                <th><a href="index.php?action=ordenarArchivoPorFechaAsc">Fecha (Asc)</a><a href="index.php?action=ordenarArchivoPorFechaDesc"> (Desc)</a></th>
                <th>Importe</th>
                <th>Operaciones</th>
            </tr>
        
       
            <?php 
            $id=0;
            foreach ($registros as $registro): ?>

            <tr>
                <td><?= $registro['concepto'] ?></td>
                <td><?= $registro['fecha'] ?></td>
                <td><?= $registro['importe'] ?></td>
                <td>
                <button class="verde"><a  href="index.php?action=borrarRegistro&concepto=<?= $registro['concepto'] ?>&fecha=<?= $registro['fecha'] ?>">Borrar</a>
                <button><a href='index.php?&concepto=<?= $registro['concepto'] ?>&fecha=<?= $registro['fecha'] ?>&importe=<?= $registro['importe'] ?>&id=$id&action=muestraeditarRegistro'>Editar</a></button>
               
                </td>
            </tr>

            <?php $id++;
            endforeach; ?>
            <tr>
   
            <form method="post" action="index.php?action=agregarRegistro">
                <td><input class="border" type="text" name="concepto" id="concepto" placeholder="Concepto"></td>
                <td><input class="border" type="text" name="fecha" id="fecha" placeholder="dd-mm-yyyy"></td>
                <td><input class="border" type="text" step="0.01" name="importe" id="importe" placeholder="Importe"></td>
                <td><input class="verde" type="submit" name="agregar" value="Agregar"></td>
                </form>
            </tr>
            
            
            
        
    </table>
    <?php if (isset($errores)): ?>
            <ul style="color: red;">
                <?php foreach ($errores as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
    <?php endif; ?>
    
        <form id="buscar" method="post" action="index.php?action=buscarConcepto">
        <input type="text" name="busqueda" placeholder="Buscar por concepto">
        <input class="verde" type="submit" value="Buscar">
        </form>

    </div>

    

    
</body>
</html>