<?php
class logica_img
{
    public function __construct($archivo)
    {
        $this->archivo = $archivo;
        $this->errores = [];
    }
    public function validarArchivo()
    {
        $verficacion_img = mime_content_type($this->archivo['tmp_name']);
        if ($verficacion_img != 'image/jpeg' && $verficacion_img != 'image/png' && $verficacion_img != 'image/gif') {
            $this->errores[] = 'Tipo de archivo no soportado';
            return false;
        }
        if ($this->archivo['size'] > 1048576) {
            $this->errores[] = 'Tamaño de archivo no soportado';
            return false;
        }
        return true;
    }
    public function guardarArchivo($ruta_img)
    {
        $validacion = $this->validarArchivo();
        if ($validacion) {
            $nuevo_nombre = uniqid() . '.' . pathinfo($this->archivo['name'], PATHINFO_EXTENSION);
            $ruta_img = $ruta_img . '/' . $nuevo_nombre;
            move_uploaded_file($this->archivo['tmp_name'], $ruta_img);
        } else {
            return false;
        }
        return $nuevo_nombre;
    }
    public function obtenerErrores()
    {
        return $this->errores;
    }
}
?>