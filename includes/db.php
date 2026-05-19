<?php
/**
 * Archivo de conexión a la base de datos MySQL
 * PrintCraft - Sistema de Cotización y Gestión de Impresión 3D
 * * Este archivo establece la conexión con la base de datos MySQL
 * utilizando PDO (PHP Data Objects) para mayor seguridad y portabilidad.
 * * @author PrintCraft Development Team
 * @version 1.0.0
 */

// Configuración de la base de datos para Hostinger
define('DB_HOST', 'localhost');
define('DB_NAME', 'u736179347_db_cotiza3d');
define('DB_USER', 'u736179347_db_cotiza3d');
define('DB_PASS', 'R;GP-4n4');
define('DB_CHARSET', 'utf8mb4');

/**
 * Obtiene una conexión PDO a la base de datos
 * * @return PDO Retorna el objeto de conexión PDO
 * @throws PDOException Si hay error en la conexión
 */
function getDBConnection() {
    try {
        // Cadena de conexión DSN (Data Source Name)
        $dsn = sprintf(
            "mysql:host=%s;dbname=%s;charset=%s",
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );
        
        // Opciones de PDO para configuración avanzada
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Lanzar excepciones en errores
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Fetch como array asociativo
            PDO::ATTR_EMULATE_PREPARES   => false,                    // Usar prepared statements reales
        ];
        
        // Crear y retornar la conexión PDO
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        
        return $pdo;
        
    } catch (PDOException $e) {
        // En producción, registrar el error en un log y mostrar mensaje genérico
        error_log("Error de conexión a la base de datos: " . $e->getMessage());
        
        // Respuesta JSON para el frontend
        http_response_code(503);
        echo json_encode([
            'success' => false,
            'error'   => 'Error de conexión con la base de datos',
            'message' => 'Por favor, contacte al administrador del sistema'
        ]);
        exit;
    }
}

/**
 * Envía una respuesta JSON al cliente
 * * @param bool   $success Indica si la operación fue exitosa
 * @param mixed  $data    Datos a enviar (opcional)
 * @param string $message Mensaje descriptivo (opcional)
 * @param int    $code    Código HTTP (opcional)
 */
function jsonResponse($success, $data = null, $message = '', $code = 200) {
    http_response_code($code);
    
    $response = [
        'success' => $success,
        'timestamp' => date('c')  // ISO 8601
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    if ($message) {
        $response['message'] = $message;
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Verifica si el usuario tiene el rol requerido
 * * @param array $allowedRoles Array de roles permitidos
 * @return bool
 */
function verifyRole($allowedRoles) {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        jsonResponse(false, null, 'No autenticado', 401);
    }
    
    if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $allowedRoles)) {
        jsonResponse(false, null, 'Acceso denegado', 403);
    }
    
    return true;
}

/**
 * Escapa string para uso en SQL (alternativa rápida a prepared statements)
 * * @param PDO    $pdo   Conexión PDO
 * @param string $value Valor a escapar
 * @return string Valor escapado
 */
function escapeString($pdo, $value) {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}
?>