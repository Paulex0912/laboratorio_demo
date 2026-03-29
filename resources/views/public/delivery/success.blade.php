<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conformidad Aceptada</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f4f7fb] font-sans text-gray-800 antialiased h-screen flex justify-center items-center">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
            <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
            ¡Muchas gracias!
        </h2>
        <p class="text-gray-600 mb-6">
            La conformidad de la Orden #OT-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} ha sido registrada de forma exitosa.
        </p>
        <p class="text-sm text-gray-500">
            Ya puedes cerrar esta ventana.
        </p>
    </div>
</body>
</html>
