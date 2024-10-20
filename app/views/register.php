<?php
require_once '../controllers/ProvinceController.php'; 
$loadProvinces = new LoadProvinces();
$provinces = $loadProvinces->getProvinces();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://userpractice.com/public/register.css">
    <title>Registro de Usuario</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form action="../controllers/registerController.php" method="POST">
        <label for="name">Nombre:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        <label for="lastname">Apellido:</label><br>
        <input type="text" id="lastname" name="lastname" required><br><br>
        <label for="username">Escoja un nombre de usuario:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="province">Provincia:</label><br>
        <select id="province" name="province">
        <?php
        if (!empty($provinces) && is_array($provinces)) {
            foreach ($provinces as $province) {
                $province_id = isset($province['id']) ? $province['id'] : null;
                $province_name = isset($province['name']) ? $province['name'] : null;

                if ($province_id !== null && $province_name !== null) {
                    ?>
                    <option value="<?php echo htmlspecialchars($province_id); ?>">
                        <?php echo htmlspecialchars($province_name); ?>
                    </option>
                    <?php
                }
            }
            } else {
                echo '<option>No hay provincias disponibles</option>';
            }
            ?>
        </select>
        <label for="phone">Número de teléfono:</label><br>
        <input type="text" id="phone" name="phone" required><br><br>
        <label for="direction">Direccion:</label><br>
        <input type="text" id="address" name="address" required><br><br>
        <button type="submit" name="action" value="save">Registrar</button>
    </form>
</body>

</html>