<?php
// Funciones de apoyo compartidas por todas las vistas.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Escapa texto para imprimirlo de forma segura en HTML. */
function e($valor): string
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

/** Guarda un mensaje para mostrarlo después de una redirección. */
function setFlash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

/** Lee (y borra) el mensaje guardado. */
function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

/**
 * Conecta a la base de datos. Si falla, muestra un mensaje claro en vez de un error fatal.
 * Requiere haber incluido antes config/database.php.
 */
function conectarDB(): PDO
{
    try {
        return (new Database())->getConnection();
    } catch (PDOException $ex) {
        http_response_code(500);
        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Error de conexión</title>'
           . '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>'
           . '<body class="bg-light"><div class="container py-5" style="max-width:640px">'
           . '<div class="alert alert-danger"><h4 class="alert-heading">No se pudo conectar a la base de datos</h4>'
           . '<p>Verifica que MySQL esté encendido, que la base esté importada y que las credenciales de '
           . '<code>config/database.php</code> sean correctas.</p></div></div></body></html>';
        exit;
    }
}
