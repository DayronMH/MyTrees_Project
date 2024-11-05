<?php
require_once '../../app/models/treeUpdateModel.php';
require_once '../../app/models/usersModel.php';
require_once '../../app/models/treesModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function sendTreesNotifications()
{
    try {
        $user = new usersModel();
        $model = new treesUpdatesModel();
        $outdatedTrees = $model->getOutdatedTrees();
        $admins = $user->getAdmins();
        $list = "";

        foreach ($outdatedTrees as $tree) {
            $list .= "ID: {$tree['tree_id']} - Especie: {$tree['commercial_name']} - Última actualización: {$tree['update_date']}\n------------------------\n";
        }
        $to = $admins[0]['email'];
        echo $admins[0]['email'];
        $subject = "Subject of the email";
        $headers = "From: mytrees.com\r\n";
        $message = "Estimado Administrador,\n\n";
        $message .= "Los siguientes árboles necesitan actualización:\n\n";
        $message .= $list;
        $message .= "\nPor favor, revise y actualice la información.\n";

        if (mail($to, $subject, $message, $headers)) {
            echo "Email enviado correctamente.";
        } else {
            echo "Error al enviar el correo.";
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        error_log("Error en notificación de árboles: " . $e->getMessage());
    }
}

function sendTreesNotificationsTest()
{
    try {
        $user = new usersModel();
        $model = new treesModel();
        $trees = [];
        $trees = $model->getAllTreesWithSpeciesAndUpdates();
        $admins = $user->getAdmins();
        $list = "";
        foreach ($trees as $tree) {
            $list .= "ID: {$tree['tree_id']} - Especie: {$tree['commercial_name']} - Última actualización: {$tree['update_date']}\n------------------------\n";
        }
        $to = $admins[0]['email'];
        echo $admins[0]['email'];
        $subject = "Subject of the email";
        $headers = "From: mytrees.com\r\n";
        $message = "Estimado Administrador,\n\n";
        $message .= "Los siguientes árboles necesitan actualización:\n\n";
        $message .= $list;
        $message .= "\nPor favor, revise y actualice la información.\n";

        if (mail($to, $subject, $message, $headers)) {
            echo "Email enviado correctamente.";
        } else {
            echo "Error al enviar el correo.";
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        error_log("Error en notificación de árboles: " . $e->getMessage());
    }
}

//sendTreesNotificationsTest();
//sendTreesNotifications();
