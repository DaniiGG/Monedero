<?php
namespace Models;
class Monedero {
    private $filePath;
    private $registros = [];

    /**
     * Constructor de la clase Monedero.
     * @param string $filePath Ruta del archivo donde se almacenan los registros.
     */
    public function __construct($filePath) {
        $this->filePath = $filePath;
        $this->getRegistros();
    }

    /**
     * Lee los registros financieros desde el archivo y los almacena en la propiedad $registros.
     * @return array Arreglo de registros financieros.
     */
    public function getRegistros() {
        $registros = [];
        if (file_exists($this->filePath)) {
            $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $campos = explode(',', $line);
                if (count($campos) === 3) {
                    $registro = [
                        'concepto' => $campos[0],
                        'fecha' => $campos[1],
                        'importe' => floatval($campos[2]),
                    ];
                    $registros[] = $registro;
                }
            }
        }
        return $registros;
    }

    /**
     * Agrega un nuevo registro financiero al archivo.
     * @param string $concepto Concepto del registro.
     * @param string $fecha Fecha del registro.
     * @param float $importe Importe del registro.
     */
    public function agregarRegistro($concepto, $fecha, $importe) {
        $registro = "$concepto,$fecha,$importe";
        file_put_contents($this->filePath, $registro . PHP_EOL, FILE_APPEND);
    }

    /**
     * Calcula el saldo total de los registros financieros.
     * @return float Saldo total.
     */
    public function calcularBalance() {
        $registros = file('data/monedero.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $balance = 0;
    
        foreach ($registros as $registro) {
            list($concepto, $fecha, $importe) = explode(',', $registro);
            $balance += (float)$importe;
        }
    
        return $balance;
    }


    /**
     * Calcula el número total de transacciones en el monedero.
     * @return int Número total de transacciones.
     */
    public function calcularTotalTransacciones() {
        $registros = file('data/monedero.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return count($registros);
    }

    /**
     * Borra un registro financiero con un concepto específico.
     * @param string $conceptoABorrar Concepto del registro a borrar.
     * @return bool True si se encontró y borró el registro, False si no se encontró.
     */
    public function borrarRegistro($conceptoABorrar, $fechaABorrar) { 
        $registros = $this->getRegistros();
        $registroEncontrado = false;
        foreach ($registros as $clave => $registro) {
            if ($registro['concepto'] === $conceptoABorrar && $registro['fecha'] === $fechaABorrar) {
           
                unset($registros[$clave]);
                $registroEncontrado = true;
                break; 
            }
        } 
        if ($registroEncontrado) {
            $this->guardarRegistros($registros);
            return true; 
        } else {
            return false; 
        }
    }


    /**
     * Guarda los registros financieros en el archivo.
     * @param array $registros Arreglo de registros financieros.
     */
    public function guardarRegistros($registros) {
        $fileContent = '';
        foreach ($registros as $registro) {
            $fileContent .= $registro['concepto'] . ',' . $registro['fecha'] . ',' . $registro['importe'] . PHP_EOL;
        }

        file_put_contents($this->filePath, $fileContent, LOCK_EX);
    }


    /**
 * Edita un registro financiero existente.
 * @param int $id Índice del registro a editar.
 * @param string $nuevoConcepto Nuevo concepto del registro.
 * @param string $nuevaFecha Nueva fecha del registro.
 * @param float $nuevoImporte Nuevo importe del registro.
 */
public function editarRegistro($id, $nuevoConcepto, $nuevaFecha, $nuevoImporte) {
    $registros = $this->getRegistros();

    if (!empty($nuevoConcepto)) {
        $registros[$id] = [
            'concepto' => $nuevoConcepto,
            'fecha' => $nuevaFecha,
            'importe' => $nuevoImporte,
        ];

        $this->guardarRegistros($registros);
    }
}





    /**
     * Ordena los registros financieros por concepto alfabéticamente.
     */
    public function ordenarPorConcepto() {
        $registros = $this->getRegistros();
    
        usort($registros, function($a, $b) {
            return strcmp($a['concepto'], $b['concepto']);
        });
    
        $fileContent = '';
        foreach ($registros as $registro) {
            $fileContent .= $registro['concepto'] . ',' . $registro['fecha'] . ',' . $registro['importe'] . PHP_EOL;
        }
    
        file_put_contents($this->filePath, $fileContent, LOCK_EX);
    }
    

    /**
     * Ordena los registros financieros por fecha en orden cronológico.
     */
    public function ordenarArchivoPorFechaAsc() {
        $registros = $this->getRegistros();
    
        usort($registros, function($a, $b) {
            return strtotime($a['fecha']) - strtotime($b['fecha']);
        });
    
        $fileContent = '';
        foreach ($registros as $registro) {
            $fileContent .= $registro['concepto'] . ',' . $registro['fecha'] . ',' . $registro['importe'] . PHP_EOL;
        }
    
        file_put_contents($this->filePath, $fileContent, LOCK_EX);
    }

    /**
     * Ordena los registros financieros por fecha en orden anticronológico.
     */
    public function ordenarArchivoPorFechaDesc() {
        $registros = $this->getRegistros();
    
        usort($registros, function($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']);
        });
    
        $fileContent = '';
        foreach ($registros as $registro) {
            $fileContent .= $registro['concepto'] . ',' . $registro['fecha'] . ',' . $registro['importe'] . PHP_EOL;
        }
    
        file_put_contents($this->filePath, $fileContent, LOCK_EX);
    }

    
        /**
     * Busca registros en el monedero cuyos conceptos coinciden parcialmente con la cadena de búsqueda proporcionada.
     *
     * @param string $busqueda Cadena de búsqueda para encontrar conceptos en los registros del monedero.
     *
     * @return array Un array de registros cuyos conceptos coinciden parcialmente con la cadena de búsqueda.
     */
    public function buscarPorConcepto($busqueda) {
        $registros = $this->getRegistros();
        $busqueda = strtolower($busqueda); 
        $resultados = [];
    
        foreach ($registros as $registro) {
            $concepto = strtolower($registro['concepto']); 
            if (strpos($concepto, $busqueda) !== false) {
                $resultados[] = $registro;
            }
        }
    
        return $resultados;
    }


}


?>