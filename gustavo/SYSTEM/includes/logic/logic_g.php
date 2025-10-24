<?php class logica_bd
{
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }
    public function obtenerUsuario($nick, $pass)
    {
        try {
            $sent_usuario = $this->conexion->prepare('SELECT * FROM usuario WHERE nombre_usuario = ? AND pass_usuario = ?');
            if ($sent_usuario === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->conexion->error);}
            $sent_usuario->bind_param('ss', $nick, $pass);
            $sent_usuario->execute();
            $res_usuario = $sent_usuario->get_result();
            $sent_usuario->close();
            if ($res_usuario->num_rows > 0) {
                return $res_usuario->fetch_assoc();} 
            else {
                return null;}
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;}
    }
    public function obtenerRegistro($sql, $tipos = '', $parametros = [])
    {
        try {
            $sent_registros = $this->conexion->prepare($sql);
            if ($sent_registros === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->conexion->error);}
            if (!empty($tipos) && !empty($parametros)) {
                $sent_registros->bind_param($tipos, ...$parametros);}
            $sent_registros->execute();
            $res_registros = $sent_registros->get_result();
            $datos = [];
            while ($fila = $res_registros->fetch_assoc()) {
                $datos[] = $fila;}
            $sent_registros->close();
            return $datos;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];}
    }
    public function existeEnTabla($tabla, $columna, $valor)
    {
        try {
            $sent_verificar = $this->conexion->prepare("SELECT COUNT(*) FROM {$tabla} WHERE {$columna} = ?");
            if ($sent_verificar === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->conexion->error);}
            $sent_verificar->bind_param('s', $valor);
            $sent_verificar->execute();
            $res_verificar = $sent_verificar->get_result();
            $fila_verificar = $res_verificar->fetch_row();
            $cantidad = $fila_verificar[0];
            $sent_verificar->close();
            return ($cantidad > 0);} 
        catch (Exception $e) {
            error_log($e->getMessage());
            return false;}
    }
    public function accionRegistro($sql, $tipos, $parametros)
    {
        try {
            $sent_accion = $this->conexion->prepare($sql);
            if ($sent_accion === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->conexion->error);}
            if (!empty($tipos) && !empty($parametros)) {
                $sent_accion->bind_param($tipos, ...$parametros);}
            $res_accion = $sent_accion->execute();
            if ($res_accion === false) {
                throw new Exception('Error al ejecutar la consulta: ' . $sent_accion->error);}
            $last_id = $this->conexion->insert_id; 
            $sent_accion->close();
            return ['success' => true, 'insert_id' => $last_id];} 
        catch (Exception $e) {
            error_log($e->getMessage());
            return ['success' => false, 'insert_id' => 0];}
    }
    public function pagina($sql, $tipos_base, $parametros_base, $pagina, $registrosPorPagina)
    {
        $offset = ($pagina - 1) * $registrosPorPagina;
        $sql_pagina = $sql . ' LIMIT ? OFFSET ?';
        $parametros_pagina = array_merge($parametros_base, [$registrosPorPagina, $offset]);
        $tipos_pagina = $tipos_base . 'ii';
        return $this->obtenerRegistro($sql_pagina, $tipos_pagina, $parametros_pagina);}
    public function contador($sqlTotal, $tipos = '', $parametros = [])
    {
        try {
            $sent_total = $this->conexion->prepare($sqlTotal);
            if ($sent_total === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->conexion->error);}
            if (!empty($tipos) && !empty($parametros)) {
                $sent_total->bind_param($tipos, ...$parametros);}
            $sent_total->execute();
            $res_total = $sent_total->get_result();
            $fila = $res_total->fetch_row();
            $totalRegistros = $fila[0] ?? 0;
            $sent_total->close();
            return $totalRegistros;} 
        catch (Exception $e) {
            error_log($e->getMessage());
            return 0;}
    }
    public function obtenerRegistrosPorPagina($sql, $sql_conteo, $por_pagina, $columnas_busqueda, $orden)
    {
        $nro_registros = 0;
        $term_busqueda = isset($_GET['b']) ? trim($_GET['b']) : '';
        $paginaActual = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $parametros = [];
        $tipos = '';
        if (!empty($term_busqueda)) {
            $conector = (strpos(strtoupper($sql_conteo), 'WHERE') !== false) ? ' AND ' : ' WHERE ';
            $condicion_busqueda = implode(" LIKE ? OR ", array_keys($columnas_busqueda)) . " LIKE ?";
            $sql_conteo .= $conector . $condicion_busqueda;
            $sql .= $conector . $condicion_busqueda;
            $tipos = str_repeat('s', count($columnas_busqueda));
            $parametros = array_fill(0, count($columnas_busqueda), "%" . $term_busqueda . "%");}
        $nro_registros = $this->contador($sql_conteo, $tipos, $parametros);
        $total_paginas = ceil($nro_registros / $por_pagina);
        $registros = [];
        if ($nro_registros > 0) {
            $offset = ($paginaActual - 1) * $por_pagina;
            if (!empty($orden)) {
                $sql .= " ORDER BY {$orden} DESC";}
            $sql .= " LIMIT ? OFFSET ?";
            $parametros[] = $por_pagina;
            $parametros[] = $offset;
            $tipos .= 'ii';
            $registros = $this->obtenerRegistro($sql, $tipos, $parametros);}
        return [
            'registros' => $registros,
            'nro_registros' => $nro_registros,
            'total_paginas' => $total_paginas,
            'paginaActual' => $paginaActual
        ];
    }
    public function iniciarTransaccion()
    {
        return $this->conexion->autocommit(FALSE);
    }
    public function confirmarTransaccion()
    {
        $e_transaccion = $this->conexion->commit();
        $this->conexion->autocommit(TRUE);
        return $e_transaccion;
    }
    public function revertirTransaccion()
    {
        $e_transaccion = $this->conexion->rollback();
        $this->conexion->autocommit(TRUE);
        return $e_transaccion;
    }
}
$bd = new logica_bd($conexion); ?>