<?php
require_once __DIR__ . '/../../includes/funciones.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = conectarDB();
$tiendaModel = new Tienda($db);

$id = $_GET['id'] ?? '';
$tienda = $tiendaModel->getById($id);
if (!$tienda) {
    setFlash('danger', 'La tienda solicitada no existe.');
    header('Location: index.php');
    exit;
}

$datos = $tienda; // formulario precargado
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = array_merge($tienda, $_POST, ['id_tienda' => $tienda['id_tienda']]);
    try {
        $tiendaModel->update($tienda['id_tienda'], $_POST);
        setFlash('success', 'Tienda actualizada correctamente.');
        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException | RuntimeException $ex) {
        $error = $ex->getMessage();
    }
}

$editando     = true;
$base         = '../../';
$pagina       = 'tiendas';
$tituloPagina = 'Editar tienda';
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/_form.php';
include __DIR__ . '/../../includes/footer.php';
