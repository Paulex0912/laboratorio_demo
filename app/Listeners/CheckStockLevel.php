<?php

namespace App\Listeners;

use App\Events\StockBelowMinimum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CheckStockLevel
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    //
    }

    /**
     * Handle the event.
     */
    public function handle(StockBelowMinimum $event): void
    {
        $product = $event->product;

        // Log para auditoría (luego se puede cambiar por un envío de email a almaceneros)
        Log::warning('Alerta de Stock Bajo', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'stock_current' => $product->stock_current,
            'stock_min' => $product->stock_min
        ]);

    // Aquí iría el envío de notificación por correo o push.
    }
}
