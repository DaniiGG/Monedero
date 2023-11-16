<!DOCTYPE html>
<html>
<head>
    <title>Transacción</title>
    
    <link rel="stylesheet" type="text/css" href="CSS/buscaConcepto.css">
</head>
<body>
    <h1>Mi monedero</h1>
<div>
<table>
<h1 id="transac"><img height="27" width="27" src="img/4564330.png"> &nbsp; &nbsp; &nbsp;Búsqueda &nbsp; &nbsp; &nbsp;<img height="27" width="27" src="img/4564330.png"></h1>
        
            <tr>
                <th>Concepto</th>
                <th>Fecha</th>
                <th>Importe</th>
                <th>Operaciones</th>
            </tr>
        
        
            <?php foreach ($resultados as $registro): ?>
            <tr>
                <td><?= $registro['concepto'] ?></td>
                <td><?= $registro['fecha'] ?></td>
                <td><?= $registro['importe'] ?></td>
                <td>
                <button class="verde"><a  href="index.php?action=borrarRegistro&concepto=<?= $registro['concepto'] ?>&fecha=<?= $registro['fecha'] ?>">Borrar</a>
                <button><a href='index.php?&concepto=<?= $registro['concepto'] ?>&fecha=<?= $registro['fecha'] ?>&importe=<?= $registro['importe'] ?>&id=$id&action=muestraeditarRegistro'>Editar</a></button>
                </td>
            </tr>
            <?php endforeach; ?>

            <form method="post" action="index.php?action=agregarRegistro">
                <td><input type="text" name="concepto" id="concepto" placeholder="Concepto"></td>
                <td><input type="text" name="fecha" id="fecha" placeholder="dd-mm-yyyy"></td>
                <td><input type="text" step="0.01" name="importe" id="importe" placeholder="Importe"></td>
                <td><input class="verde" type="submit" name="agregar" value="Agregar"></td>
                </form>
            </tr>
        
    </table>

</div>
<button class="verde"><a href="index.php">Ver todas Las transacciones</a></button>
    
</body>
</html>