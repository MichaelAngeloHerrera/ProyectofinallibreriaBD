<?php
require_once __DIR__ . '/../../includes/funciones.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = conectarDB();
$tituloModel = new Titulo($db);
$publicadores = $tituloModel->getPublicadores();

$id = $_GET['id'] ?? '';
$titulo = $tituloModel->getById($id);
if (!$titulo) {
    setFlash('danger', 'El título solicitado no existe.');
    header('Location: index.php');
    exit;
}

$datos = $titulo; // formulario precargado
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = array_merge($titulo, $_POST, ['id_titulo' => $titulo['id_titulo']]);
    try {
        $tituloModel->update($titulo['id_titulo'], $_POST);
        setFlash('success', 'Título actualizado correctamente.');
        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException | RuntimeException $ex) {
        $error = $ex->getMessage();
    }
}

$editando     = true;
$base         = '../../';
$pagina       = 'titulos';
$tituloPagina = 'Editar título';
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/_form.php';
include __DIR__ . '/../../includes/footer.php';
