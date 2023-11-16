<?php
namespace Controllers;

use DateTime;
use Models\Monedero;
use Lib\Pages;


class MonederoController {
    private $monedero;
    private Pages $pages;

    /**
     * Constructor del controlador.
     * Crea una instancia de la clase Monedero y especifica el archivo donde se almacenan los registros.
     */
    public function __construct() {
        $this->monedero = new Monedero('data/monedero.txt');
        $this->pages= new Pages();
        
    }

    /**
     * Muestra los registros financieros, permite editar registros y ordena los registros por concepto o fecha.
     */

    public function mostrarRegistros() {
        
        
        $this->pages->render("muestraMonedero", ['registros' => $this->monedero->getRegistros()]);
        
        
        $this->mostrarBalanceyTransacciones();
    }


     /**
     * Agrega un nuevo registro financiero y luego muestra los registros actualizados.
     * @param string $concepto Concepto del registro.
     * @param string $fecha Fecha del registro.
     * @param float $importe Importe del registro.
     */
    public function agregarRegistro() {
        $errores = [];
        
        
        if (isset($_POST['agregar'])) {
            // Obtener y sanear los datos del formulario
            $concepto = filter_input(INPUT_POST, 'concepto', FILTER_SANITIZE_STRING);
            $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);
            $importe = filter_input(INPUT_POST, 'importe', FILTER_VALIDATE_FLOAT);
        
            // Validar los datos
            if (!$concepto) {
                $errores[] = "El campo 'Concepto' no puede estar vacío.";
            }
            if (!$fecha) {
                $errores[] = "El campo 'Fecha' no puede estar vacío.";
            } else {
           
                $fechaObj = DateTime::createFromFormat('d-m-Y', $fecha);
        
                if (!$fechaObj || $fechaObj->format('d-m-Y') !== $fecha) {
                    $errores[] = "El campo 'Fecha' debe tener el formato dd-mm-yyyy.";
                }
            }
            if ($importe === false) {
                $errores[] = "El campo 'Importe' debe ser un número válido.";
            }
            if (!empty($errores)) {
                $this->pages->render("muestraMonedero", ["errores"=>$errores,'registros' => $this->monedero->getRegistros()]);
            } else {
                $this->monedero->agregarRegistro($concepto, $fecha, $importe);
                header('Location: index.php');
            }
        }
    }

    /**
     * Esta funcion muestra los registros que se quieren editar
     */
    public function muestraeditarRegistro() {
       
        
        $this->mostrarRegistros();
        
        if (isset($_GET['concepto']) && isset($_GET['fecha']) && isset($_GET['importe']) && isset($_GET['id'])) {
            $concepto = $_GET['concepto'];
            $fecha = $_GET['fecha'];
            $importe = $_GET['importe'];
            $id = $_GET['id'];
    
            $this->pages->render("editarRegistro", [
                'concepto' => $concepto,
                'fecha' => $fecha,
                'importe' => $importe,
                'id' => $id
            ]);
            $this->borrarRegistrosEditar();
        } 
    }

    /**
     * esta funcion recoge los datos esditado y los actualiza en la base de datos
     */
    public function editarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['id']) && isset($_POST['nuevoConcepto']) && isset($_POST['nuevaFecha']) && isset($_POST['nuevoImporte'])) {
                
                $id = $_POST['id'];
                $nuevoConcepto = $_POST['nuevoConcepto'];
                $nuevaFecha = $_POST['nuevaFecha'];
                $nuevoImporte = $_POST['nuevoImporte'];

                
                
                if (!empty($nuevoConcepto) && !empty($nuevaFecha) && is_numeric($nuevoImporte)) {
                    $this->monedero->editarRegistro($id, $nuevoConcepto, $nuevaFecha, $nuevoImporte);
                    
                }
                
            }
    
            // Redirige a la página principal después de editar el registro
        $this->mostrarRegistros();
        
        }
    }

    /**
     * Muestra el balance y el número total de transacciones en el monedero.
     */
    public function mostrarBalanceyTransacciones() {
        
        $balance = $this->monedero->calcularBalance(); 
        $totalTransacciones= $this->monedero->calcularTotalTransacciones();
        include 'Views/BalanceyTransacciones.php';
    }

     /**
     * Borra un registro financiero si se envía una solicitud POST para eliminarlo.
     */
    public function borrarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['concepto'])&&($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fecha']))) {
            $conceptoABorrar = $_GET['concepto'];
            $fechaABorrar = $_GET['fecha'];
            $this->monedero->borrarRegistro($conceptoABorrar, $fechaABorrar);
            header('Location: index.php');
            
        }
    }

    /**
     * Esta función hace lo mismo que la anterio pero sin usar el header, la uso en editar registro
     */
    public function borrarRegistrosEditar() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['concepto'])&&($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fecha']))) {
            $conceptoABorrar = $_GET['concepto'];
            $fechaABorrar = $_GET['fecha'];
            $this->monedero->borrarRegistro($conceptoABorrar, $fechaABorrar);
            
            
        }
    }


    /**
     * Ordena los registros financieros por concepto si se envía una solicitud POST para ordenar.
     */
    public function ordenarPorConcepto() {
       
        $this->monedero->ordenarPorConcepto();
        header('Location: index.php');
        
    }


    /**
     * Ordena los registros financieros por fecha si se envía una solicitud POST para ordenar por fecha.
     */
   public function ordenarArchivoPorFechaAsc(){
   
    $this->monedero->ordenarArchivoPorFechaAsc();
    header('Location: index.php');
   
    }


    /**
     * Ordena los registros financieros por fecha si se envía una solicitud POST para ordenar por fecha.
     */
   public function ordenarArchivoPorFechaDesc(){
   
    $this->monedero->ordenarArchivoPorFechaDesc();
    header('Location: index.php');
   
    }


    /**
     * Se encarga de buscar un registro en función del concepto mostrando otra vista.
     */
    public function buscarConcepto() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['busqueda'])) {
            $busqueda = $_POST['busqueda'];
            $resultados = $this->monedero->buscarPorConcepto($busqueda);
            $this->borrarRegistro();
        
            
        
       include 'Views/buscaConcepto.php';
        }
    }

}