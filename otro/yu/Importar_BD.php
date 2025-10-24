<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////

/** Configuración de importación de la Base de Datos
 *------------------------------------------------------*/
$database = $_POST["bd"];

/**
 * Define database parameters here
 */
define("DB_USER", 'root');
define("DB_PASSWORD", '');
define("DB_NAME", 'vertex');
define("DB_HOST", 'localhost');
define("BACKUP_DIR", __DIR__ . '/Respaldos'); // Ruta absoluta al directorio de respaldos
define("BACKUP_FILE", $database); // El script autodeterminará si el archivo de respaldo está comprimido en gzip basado en la extensión .gz
define("CHARSET", 'utf8');
define("DISABLE_FOREIGN_KEY_CHECKS", true); // Establece a true si tienes fallos de restricción de clave externa

/**
 * La clase Restore_Database
 */
class Restore_Database {
    /**
     * Host donde se encuentra la base de datos
     */
    public $host;

    /**
     * Nombre de usuario utilizado para conectar a la base de datos
     */
    public $username;

    /**
     * Contraseña utilizada para conectar a la base de datos
     */
    public $passwd;

    /**
     * Base de datos a respaldar
     */
    public $dbName;

    /**
     * Charset de la base de datos
     */
    public $charset;

    /**
     * Conexión a la base de datos
     */
    public $conn;

    /**
     * Desactivar la verificación de claves foráneas
     */
    public $disableForeignKeyChecks;

    /**
     * Directorio de respaldo
     */
    public $backupDir;

    /**
     * Archivo de respaldo
     */
    public $backupFile;

    /**
     * Constructor inicializa la base de datos
     */
    function __construct($host, $username, $passwd, $dbName, $charset = 'utf8') {
        $this->host                    = $host;
        $this->username                = $username;
        $this->passwd                  = $passwd;
        $this->dbName                  = $dbName;
        $this->charset                 = $charset;
        $this->disableForeignKeyChecks = defined('DISABLE_FOREIGN_KEY_CHECKS') ? DISABLE_FOREIGN_KEY_CHECKS : true;
        $this->conn                    = $this->initializeDatabase();
        $this->backupDir               = defined('BACKUP_DIR') ? BACKUP_DIR : '.';
        $this->backupFile              = defined('BACKUP_FILE') ? BACKUP_FILE : null;
    }

    /**
     * Destructor vuelve a habilitar las comprobaciones de clave externa
     */
    function __destruct() {
        /**
         * Volver a habilitar las comprobaciones de clave externa
         */
        if ($this->disableForeignKeyChecks === true) {
            mysqli_query($this->conn, 'SET foreign_key_checks = 1');
        }
    }

    protected function initializeDatabase() {
        try {
            $conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
            if (mysqli_connect_errno()) {
                throw new Exception('ERROR conectando a la base de datos: ' . mysqli_connect_error());
            }
            if (!mysqli_set_charset($conn, $this->charset)) {
                mysqli_query($conn, 'SET NAMES ' . $this->charset);
            }

            /**
             * Desactivar las comprobaciones de clave externa
             */
            if ($this->disableForeignKeyChecks === true) {
                mysqli_query($conn, 'SET foreign_key_checks = 0');
            }

        } catch (Exception $e) {
            print_r($e->getMessage());
            die();
        }

        return $conn;
    }

    /**
     * Restaura la base de datos desde un archivo
     */
    public function restoreDb() {
        try {
            $sql = '';
            $multiLineComment = false;

            $backupDir = $this->backupDir;
            $backupFile = $this->backupFile;

            // Construir la ruta completa del archivo de respaldo
            $fullBackupFilePath = $backupDir . '/' . $backupFile;

            /**
             * Descomprimir el archivo si está gzipped
             */
            $backupFileIsGzipped = substr($backupFile, -3, 3) == '.gz' ? true : false;
            if ($backupFileIsGzipped) {
                if (!$backupFile = $this->gunzipBackupFile($fullBackupFilePath)) {
                    throw new Exception("ERROR: no se pudo descomprimir el archivo de respaldo " . $fullBackupFilePath);
                }
                $fullBackupFilePath = $backupDir . '/' . $backupFile; // Actualiza la ruta al archivo descomprimido
            }

            /**
             * Leer el archivo de respaldo línea por línea
             */
            $handle = fopen($fullBackupFilePath, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $line = ltrim(rtrim($line));
                    if (strlen($line) > 1) { // evitar líneas en blanco
                        $lineIsComment = false;
                        if (preg_match('/^\/\*/', $line)) {
                            $multiLineComment = true;
                            $lineIsComment = true;
                        }
                        if ($multiLineComment || preg_match('/^\/\//', $line)) {
                            $lineIsComment = true;
                        }
                        if (!$lineIsComment) {
                            $sql .= $line;
                            if (preg_match('/;$/', $line)) {
                                // ejecutar consulta
                                if (mysqli_query($this->conn, $sql)) {
                                    if (preg_match('/^CREATE TABLE `([^`]+)`/i', $sql, $tableName)) {
                                        // Puedes comentar esta línea si no quieres ver la creación de tablas
                                        // $this->obfPrint("Tabla creada exitosamente: `" . $tableName[1] . "`");
                                    }
                                    $sql = '';
                                } else {
                                    throw new Exception("ERROR: error de ejecución SQL: " . mysqli_error($this->conn));
                                }
                            }
                        } else if (preg_match('/\*\/$/', $line)) {
                            $multiLineComment = false;
                        }
                    }
                }
                fclose($handle);
            } else {
                throw new Exception("ERROR: no se pudo abrir el archivo de respaldo. Verifica la ruta y los permisos: " . $fullBackupFilePath);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }

        if ($backupFileIsGzipped) {
            // Eliminar el archivo descomprimido temporal
            unlink($fullBackupFilePath);
        }

        return true;
    }

    /*
     * Descomprime el archivo de respaldo
     *
     * @param string $sourceFilePath Ruta completa del archivo gzipped a descomprimir
     * @return string Nuevo nombre de archivo (sin .gz y sin directorio de respaldo) si tiene éxito, o false si la operación falla
     */
    protected function gunzipBackupFile($sourceFilePath) {
        $bufferSize = 4096;
        $error = false;

        $dest = $this->backupDir . '/' . date("Ymd_His", time()) . '_' . substr($this->backupFile, 0, -3);

        if (file_exists($dest)) {
            if (!unlink($dest)) {
                return false;
            }
        }

        if (!$srcFile = gzopen($sourceFilePath, 'rb')) {
            return false;
        }
        if (!$dstFile = fopen($dest, 'wb')) {
            gzclose($srcFile);
            return false;
        }

        while (!gzeof($srcFile)) {
            if (!fwrite($dstFile, gzread($srcFile, $bufferSize))) {
                fclose($dstFile);
                gzclose($srcFile);
                return false;
            }
        }

        fclose($dstFile);
        gzclose($srcFile);

        return str_replace($this->backupDir . '/', '', $dest);
    }

    /**
     * Imprime el mensaje forzando el vaciado del búfer de salida
     *
     */
    public function obfPrint ($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1) {
        if (!$msg) {
            return false;
        }

        $msg = date("Y-m-d H:i:s") . ' - ' . $msg;
        $output = '';

        if (php_sapi_name() != "cli") {
            $lineBreak = "<br />";
        } else {
            $lineBreak = "\n";
        }

        if ($lineBreaksBefore > 0) {
            for ($i = 1; $i <= $lineBreaksBefore; $i++) {
                $output .= $lineBreak;
            }
        }

        $output .= $msg;

        if ($lineBreaksAfter > 0) {
            for ($i = 1; $i <= $lineBreaksAfter; $i++) {
                $output .= $lineBreak;
            }
        }

        if (php_sapi_name() == "cli") {
            $output .= "\n";
        }

        echo $output;

        if (php_sapi_name() != "cli") {
            ob_flush();
        }

        flush();
    }
}

/**
 * Instanciar Restore_Database y realizar el respaldo
 */
error_reporting(E_ALL);
set_time_limit(900); // 15 minutos

if (php_sapi_name() != "cli") {

}

$restoreDatabase = new Restore_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$result = $restoreDatabase->restoreDb() ? 'OK' : 'KO';
// --- ESTA ES LA LÍNEA QUE SE ELIMINA O COMENTA ---
// $restoreDatabase->obfPrint("Resultado de la restauración: ".$result, 1);
// ------------------------------------------------

echo "<script>alert('Base de datos importada con éxito');</script>";

if (php_sapi_name() != "cli") {

}