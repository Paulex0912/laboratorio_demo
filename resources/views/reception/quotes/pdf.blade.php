<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #6B46C1;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header table {
            width: 100%;
            border: none;
        }
        .company-details h1 {
            color: #6B46C1;
            margin: 0 0 5px 0;
            font-size: 28px;
        }
        .company-details p {
            margin: 0;
            color: #555;
            font-size: 13px;
        }
        .quote-title h2 {
            margin: 0 0 5px 0;
            font-size: 24px;
            color: #333;
            text-align: right;
            text-transform: uppercase;
        }
        .quote-title p {
            margin: 0;
            text-align: right;
            font-weight: bold;
            color: #666;
        }
        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-section table {
            width: 100%;
            border: none;
        }
        .info-box {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #4a5568;
            font-size: 16px;
            border-bottom: 1px solid #cbd5e0;
            padding-bottom: 5px;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 13px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #6B46C1;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 13px;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .items-table .text-right {
            text-align: right;
        }
        .totals-table {
            width: 40%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 10px;
            font-size: 14px;
        }
        .totals-table .label {
            text-align: right;
            font-weight: bold;
            color: #555;
        }
        .totals-table .amount {
            text-align: right;
        }
        .totals-table .grand-total {
            background-color: #f8fafc;
            font-size: 18px;
            font-weight: bold;
            color: #6B46C1;
            border-top: 2px solid #6B46C1;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #718096;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
        .terms {
            margin-top: 40px;
            font-size: 12px;
            color: #555;
            background-color: #f8fafc;
            padding: 15px;
            border-left: 4px solid #6B46C1;
        }
        .terms h4 {
            margin: 0 0 5px 0;
            color: #333;
        }
        .terms p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <table>
                <tr>
                    <td class="company-details" style="width: 50%;">
                        <h1>Laboratorio Dental JoelDent</h1>
                        <p>RUC: 20123456789</p>
                        <p>Av. Principal 123, Lima, Perú</p>
                        <p>Tel: (01) 555-1234 | Cel: 987 654 321</p>
                        <p>contacto@joeldent.pe | www.joeldent.pe</p>
                    </td>
                    <td class="quote-title" style="width: 50%; vertical-align: top;">
                        <h2>COTIZACIÓN</h2>
                        <p>Nº QT-{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p style="font-weight: normal; margin-top: 5px;">Fecha: {{ $quote->created_at->format('d/m/Y') }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <table cellspacing="15">
                <tr>
                    <td class="info-box" style="width: 50%; vertical-align: top;">
                        <h3>Información del Cliente / Paciente</h3>
                        <p><strong>Sr(a):</strong> {{ $quote->patient->name }}</p>
                        <p><strong>DNI/RUC:</strong> {{ $quote->patient->dni ?? ($quote->patient->ruc ?? 'No registrado') }}</p>
                        @if($quote->patient->phone)
                            <p><strong>Teléfono:</strong> {{ $quote->patient->phone }}</p>
                        @endif
                        @if($quote->patient->email)
                            <p><strong>Email:</strong> {{ $quote->patient->email }}</p>
                        @endif
                    </td>
                    <td class="info-box" style="width: 50%; vertical-align: top;">
                        <h3>Detalles de la Cotización</h3>
                        <p><strong>Atendido por:</strong> {{ $quote->creator->name }}</p>
                        <p><strong>Válida hasta:</strong> {{ $quote->valid_until->format('d/m/Y') }}</p>
                        <p><strong>Estado:</strong> {{ ucfirst($quote->status) }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 45%;">Descripción del Trabajo / Tratamiento</th>
                    <th style="width: 25%;">Material / Color</th>
                    <th style="width: 25%;" class="text-right">Importe Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->lines_json as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item['type_name'] }}</strong>
                        </td>
                        <td>
                            @if(!empty($item['material']))
                                Mat: {{ $item['material'] }}<br>
                            @endif
                            @if(!empty($item['color']))
                                Color: {{ $item['color'] }}
                            @endif
                            @if(empty($item['material']) && empty($item['color']))
                                -
                            @endif
                        </td>
                        <td class="text-right font-bold text-gray-900">S/ {{ number_format($item['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="amount">S/ {{ number_format($quote->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="label">IGV (0% ref):</td>
                <td class="amount">S/ {{ number_format($quote->igv, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td class="label" style="color: #6B46C1;">TOTAL A PAGAR:</td>
                <td class="amount">S/ {{ number_format($quote->total, 2) }}</td>
            </tr>
        </table>

        <!-- Terms and Conditions -->
        <div class="terms">
            <h4>Términos y Condiciones Generales</h4>
            <p>1. Esta cotización tiene validez hasta la fecha indicada. Posteriormente los precios pueden estar sujetos a variación.</p>
            <p>2. El inicio del trabajo requiere la aprobación expresa del cliente y, dependiendo del caso, un abono inicial acordado.</p>
            <p>3. Los tiempos de entrega serán coordinados y confirmados en el momento de la generación de la Orden de Trabajo Oficial.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este documento es una estimación de precios y un presupuesto formal. No tiene validez legal como comprobante de pago.</p>
            <p>Generado por el Sistema Integral Inteligente de <strong>Laboratorio Dental JoelDent</strong> el {{ now()->format('d/m/Y H:i A') }}</p>
        </div>
    </div>
</body>
</html>
