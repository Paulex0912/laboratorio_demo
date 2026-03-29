<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderDeliveryController extends Controller
{
    /**
     * Show the firm signing form explicitly to the customer.
     */
    public function show(Request $request, WorkOrder $order)
    {
        // Require valid signature
        if (!$request->hasValidSignature()) {
            abort(401, 'Este enlace de conformidad ha expirado o no es válido.');
        }

        if ($order->status === 'entregado') {
            return view('public.delivery.already_signed', compact('order'));
        }

        return view('public.delivery.show', compact('order'));
    }

    /**
     * Process the signature submission.
     */
    public function sign(Request $request, WorkOrder $order)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Este enlace de conformidad ha expirado o no es válido.');
        }

        if ($order->status === 'entregado') {
            return redirect()->route('public.delivery.show', ['order' => $order->id, 'signature' => $request->query('signature')])
                ->with('error', 'Esta orden ya ha sido firmada y entregada.');
        }

        $request->validate([
            'signature' => 'required|string', // Base64 Canvas data
        ]);

        // Logic to extract and save the base64 image
        $image_parts = explode(";base64,", $request->signature);
        if (count($image_parts) >= 2) {
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1] ?? 'png';
            $image_base64 = base64_decode($image_parts[1]);

            $fileName = 'signatures/' . uniqid() . '.' . $image_type;

            Storage::disk('public')->put($fileName, $image_base64);

            $order->update([
                'status' => 'entregado',
                'signature_path' => $fileName,
                'signed_at' => now(),
                'delivered_at' => now()
            ]);

            // Auditable event will capture this update automatically

            return view('public.delivery.success', compact('order'));
        }

        return back()->with('error', 'La firma no pudo ser procesada. Inténtalo de nuevo.');
    }
}
