<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Venta #{{ $venta->id_venta }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            border-radius: 8px;
            overflow: hidden;
        }
        
        th, td { 
            border: 1px solid #87CEEB; 
            padding: 8px; 
            text-align: left; 
        }
        
        th { 
            background-color: #87CEEB; 
            color: #1e3a8a;
            font-weight: bold;
            text-align: center;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f0f8ff;
        }
        
        tbody tr:hover {
            background-color: #e6f3ff;
        }
        
        .total-row {
            background-color: #b8d4f0 !important;
            font-weight: bold;
            color: #1e3a8a;
        }
        
        .total-row td {
            border-top: 2px solid #4682b4;
        }
        
        .logo {
            width: 150px;
            height: 150px;
            margin-right: 20px;
            border: 3px solid white;
            border-radius: 10px;
            background-color: white;
            padding: 5px;
        }
        
        .header-simple {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4682b4;
            padding: 15px 0;
            justify-content: center;
            text-align: center
        }

        .logo-simple {
            width: 80px;
            height: 80px;
            margin-right: 20px;
            background-color: white;
            padding: 3px;
            flex-shrink: 0;
            text-align: center;
            justify-content: center
        }

        .sistema-title {
            font-size: 32px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            line-height: 1;
            display: inline-block;
        }
        h1{
            font-size: 40px
        }
        h2 {
            color: #1e3a8a;
            border-bottom: 2px solid #87CEEB;
            padding-bottom: 5px;
            font-size: 18px;
        }
        
        p strong {
            color: #4682b4;
        }
        
        .currency {
            color: #2e8b57;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header-simple">
        <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(resource_path('images/logo.jpg'))) }}" alt="Logo" class="logo-simple">
        <h1 class="sistema-title">SISTEMA POS GT</h1>
    </div>
    <h3>Venta #{{ $venta->id_venta }}</h3>

    <div class="info">
        <p><strong>Cliente:</strong> {{ $venta->cliente->nombre_cliente ?? 'Cliente no especificado' }}</p>
        <p><strong>Fecha:</strong> {{ $venta->fecha ?? now()->format('d/m/Y') }}</p>
        <p><strong>Atendido por:</strong> {{ $venta->usuario->nombre_usuario ?? 'No registrado' }}</p>
    </div>

    @if($total_detalle->isEmpty())
        <p class="text-center text-gray-500">No hay productos vendidos disponibles.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Código Producto</th>
                    <th>Producto</th>
                    <th>Lote</th>
                    <th>Presentación</th>
                    <th>Cantidad</th>
                    <th>Costo Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($total_detalle as $index => $detalle)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detalle->codigo_producto }}</td>
                        <td>{{ $detalle->nombre_producto }}</td>
                        <td>{{ $detalle->lote }}</td>
                        <td>{{ $detalle->nombre_presentacion }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>Q{{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td>Q{{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" style="text-align: right;">DESCUENTO:</td>
                    <td style="text-align: right;"><strong><span class="currency">Q{{ number_format($venta->total_descuento, 2) }}</span></strong></td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: right;">TOTAL GENERAL:</td>
                    <td style="text-align: right;"><strong><span class="currency">Q{{ number_format($total_final->total_venta, 2) }}</span></strong></td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="footer">
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
