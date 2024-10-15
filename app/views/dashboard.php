<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php
            // Aquí deberías obtener los datos reales de tu base de datos
            $amigosRegistrados = 0; // Placeholder
            $arbolesDisponibles = 0; // Placeholder
            $arbolesVendidos = 0; // Placeholder

            $estadisticas = [
                ['título' => 'Amigos Registrados', 'valor' => $amigosRegistrados, 'icono' => '👥'],
                ['título' => 'Árboles Disponibles', 'valor' => $arbolesDisponibles, 'icono' => '🌳'],
                ['título' => 'Árboles Vendidos', 'valor' => $arbolesVendidos, 'icono' => '💰'],
            ];

            foreach ($estadisticas as $estadistica) {
                echo <<<HTML
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">{$estadistica['título']}</p>
                            <p class="text-3xl font-bold">{$estadistica['valor']}</p>
                        </div>
                        <div class="text-4xl">{$estadistica['icono']}</div>
                    </div>
                </div>
                HTML;
            }
            ?>
        </div>
    </div>
</body>
</header>