<?php
require_once '../app/models/treeUpdateModel.php';
require_once 'MailHelper.php';

$treesUpdateModel = new treesUpdatesModel();
$outdatedTrees = $treesUpdateModel->getOutdatedTrees();

if (!empty($outdatedTrees)) {
    $treeList = "";
    foreach ($outdatedTrees as $tree) {
        $treeList .= "Árbol ID: " . $tree['id'] . " - Especie: " . $tree['species_id'] . "\n";
    }

    $subject = "Notificación: Árboles desactualizados";
    $message = "Los siguientes árboles no han sido actualizados desde hace más de 1 mes:\n" . $treeList;

    // Supón que el administrador tiene el ID de usuario 1 y su correo está registrado
    $adminEmail = "admin@example.com"; // Reemplaza con el correo real del administrador
    sendEmail($adminEmail, $subject, $message);

    echo "Correo enviado al administrador.";
} else {
    echo "No hay árboles desactualizados.";
}