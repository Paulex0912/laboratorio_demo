<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Firmar Conformidad de Entrega</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f4f7fb] font-sans text-gray-800 antialiased" style="font-family: 'Inter', sans-serif;">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Conformidad de Entrega
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Orden #OT-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-6 border-b border-gray-200 pb-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Detalles del Trabajo</h3>
                    <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Paciente</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->patient->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Trabajo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->type }} ({{ $order->material }})</dd>
                        </div>
                    </div>
                </div>

                <form id="signature-form" action="{{ route('public.delivery.sign', ['order' => $order->id, 'signature' => request()->query('signature')]) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Firma del Cliente / Doctor
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50 aspect-video relative">
                            <canvas id="signature-pad" class="w-full h-full cursor-crosshair touch-none"></canvas>
                        </div>
                        <div class="mt-2 flex justify-end">
                            <button type="button" id="clear-signature" class="text-xs text-gray-500 hover:text-red-500">Borrar y volver a intentar</button>
                        </div>
                    </div>

                    <input type="hidden" name="signature" id="signature-input" required>

                    <div class="mt-6">
                        <button type="submit" id="submit-btn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Firmar y Confirmar Recepción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Implementación simple de Signature Pad nativa con JS Vanilla -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signature-pad');
            const ctx = canvas.getContext('2d');
            const clearBtn = document.getElementById('clear-signature');
            const form = document.getElementById('signature-form');
            const signatureInput = document.getElementById('signature-input');
            
            // Resize canvas to match display size
            function resizeCanvas() {
                const ratio =  Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                ctx.scale(ratio, ratio);
                ctx.lineWidth = 3;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#000000';
            }
            
            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            let isDrawing = false;
            let hasSignature = false;

            function getCoordinates(e) {
                const rect = canvas.getBoundingClientRect();
                if (e.touches && e.touches.length > 0) {
                    return {
                        x: e.touches[0].clientX - rect.left,
                        y: e.touches[0].clientY - rect.top
                    };
                }
                return {
                    x: e.clientX - rect.left,
                    y: e.clientY - rect.top
                };
            }

            function startDrawing(e) {
                e.preventDefault();
                isDrawing = true;
                const coords = getCoordinates(e);
                ctx.beginPath();
                ctx.moveTo(coords.x, coords.y);
            }

            function draw(e) {
                if (!isDrawing) return;
                e.preventDefault();
                const coords = getCoordinates(e);
                ctx.lineTo(coords.x, coords.y);
                ctx.stroke();
                hasSignature = true;
            }

            function stopDrawing() {
                isDrawing = false;
            }

            // Mouse events
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            window.addEventListener('mouseup', stopDrawing);

            // Touch events
            canvas.addEventListener('touchstart', startDrawing, { passive: false });
            canvas.addEventListener('touchmove', draw, { passive: false });
            window.addEventListener('touchend', stopDrawing);

            clearBtn.addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasSignature = false;
            });

            form.addEventListener('submit', function(e) {
                if (!hasSignature) {
                    e.preventDefault();
                    alert('Por favor, dibuja tu firma antes de confirmar.');
                    return;
                }
                
                // Poner background blanco porque canvas transparente jode el base64
                const tempCanvas = document.createElement('canvas');
                tempCanvas.width = canvas.width;
                tempCanvas.height = canvas.height;
                const tCtx = tempCanvas.getContext('2d');
                tCtx.fillStyle = '#FFFFFF';
                tCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
                tCtx.drawImage(canvas, 0, 0);

                signatureInput.value = tempCanvas.toDataURL('image/png');
                document.getElementById('submit-btn').innerHTML = 'Enviando...';
                document.getElementById('submit-btn').disabled = true;
            });
        });
    </script>
</body>
</html>
