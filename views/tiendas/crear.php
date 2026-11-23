<?php
require_once __DIR__ . '/../../includes/funciones.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = conectarDB();
$tiendaModel = new Tienda($db);

$datos = ['id_tienda' => '', 'nombre_tienda' => '', 'direcc_tienda' => '', 'ciudad' => '', 'estado' => '', 'codigo_postal' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = array_merge($datos, $_POST);
    try {
        $tiendaModel->create($_POST);
        setFlash('success', 'Tienda creada correctamente.');
        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException | RuntimeException $ex) {
        $error = $ex->getMessage();
    }
}

$editando     = false;
$base         = '../../';
$pagina       = 'tiendas';
$tituloPagina = 'Nueva tienda';
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/_form.php';
include __DIR__ . '/../../includes/footer.php';
