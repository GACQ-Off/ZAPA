<?php class logica_files {
    private $directorio_destino;

    public function __construct(string $directorio_destino) {
        $this->directorio_destino = rtrim($directorio_destino, '/') . '/';
        if (!is_dir($this->directorio_destino) && !mkdir($this->directorio_destino, 0777, true)) {
            throw new Exception("Error: No se pudo crear o acceder al directorio de destino: " . $this->directorio_destino);}}
    public function procesarYGuardarImagen(array $archivo): string {
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error en la subida del archivo. Código: " . $archivo['error']);}
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombre_archivo_unico = uniqid() . time() . '.' . $extension;
        $ruta_servidor = $this->directorio_destino . $nombre_archivo_unico;
        if (move_uploaded_file($archivo['tmp_name'], $ruta_servidor)) {
            return $nombre_archivo_unico;} 
        else {
            throw new Exception("Error al mover el archivo a la carpeta de destino.");}}}?>