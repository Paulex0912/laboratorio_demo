<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orden Ya Entregada</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f4f7fb] font-sans text-gray-800 antialiased h-screen flex justify-center items-center">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center bg-white p-10 rounded-2xl shadow border border-gray-100">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-6">
            <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            Orden Completada
        </h2>
        <p class="text-gray-600">
            Esta orden de trabajo ya cuenta con una firma registrada y fue marcada como entregada.
        </p>
        
        @if($order->signature_path)
            <div class="mt-6 border p-4 rounded bg-gray-50">
                <p class="text-xs text-gray-500 mb-2 uppercase">Firma Guardada</p>
                <img src="{{ Storage::url($order->signature_path) }}" alt="Firma Registrada" class="max-h-24 mx-auto mix-blend-multiply">
            </div>
        @endif
    </div>
</body>
</html>
