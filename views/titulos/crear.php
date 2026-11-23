<?php
require_once __DIR__ . '/../../includes/funciones.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = conectarDB();
$tituloModel = new Titulo($db);
$publicadores = $tituloModel->getPublicadores();

$datos = ['id_titulo' => '', 'titulo' => '', 'tipo' => '', 'id_pub' => '', 'precio' => '', 'notas' => '', 'fecha_pub' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = array_merge($datos, $_POST);
    try {
        $tituloModel->create($_POST);
        setFlash('success', 'Título creado correctamente.');
        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException | RuntimeException $ex) {
        $error = $ex->getMessage();
    }
}

$editando     = false;
$base         = '../../';
$pagina       = 'titulos';
$tituloPagina = 'Nuevo título';
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/_form.php';
include __DIR__ . '/../../includes/footer.php';
