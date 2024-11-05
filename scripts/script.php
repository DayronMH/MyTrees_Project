<?php
require_once '../app/models/treeUpdateModel.php';
require_once '../app/models/usersModel.php';
require_once 'MailHelper.php';

// Iniciar sesión si no está iniciada
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
            $list .= "ID: " . $tree['id'] . "\n";
            $list .= "Especie: " . $tree['species_name'] . "\n";
            $list .= "Última actualización: " . $tree['last_update_date'] . "\n";
            $list .= "------------------------\n";
        }

        // Preparar el email
        $to = implode(", ", array_column($admins, 'email')); // Combina todos los emails
        $subject = "Subject of the email";
        $message = "Estimado Administrador,\n\n";
        $message .= "Los siguientes árboles necesitan actualización:\n\n";
        $message .= $list;
        $message .= "\nPor favor, revise y actualice la información.\n";

        if (mail($to, $subject, $message, )) {
            echo "Email enviado correctamente.";
        } else {
            echo "Error al enviar el correo.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        error_log("Error en notificación de árboles: " . $e->getMessage());
    }
}

// Ejecutar la función
sendTreesNotifications();
