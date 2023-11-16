<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #rojo{
            color:rgb(255, 59, 59);
        }
        </style>
</head>
<body>

    <p>Total de transacciones: <b><?php  echo $totalTransacciones; ?></b></p>
    
    <b><p id="rojo">El balance total actual es: <?= $balance ?> €</p></b>

    
</body>
</html>