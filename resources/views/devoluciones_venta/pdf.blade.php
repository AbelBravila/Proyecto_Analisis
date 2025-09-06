<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Devolución Venta #{{ $devolucion->id_devolucion_venta }}</title>
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
    
    <h2>Detalle de Devolución Venta #{{ $devolucion->id_devolucion_venta }}</h2>
    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y H:i') }}</p>
    <p><strong>Cliente:</strong> {{ $devolucion->cliente ? $devolucion->cliente->nombre_cliente : 'Cliente no encontrado' }}</p>
    <p><strong>Estado:</strong> {{ $devolucion->estado == 'A' ? 'Activo' : 'Inactivo' }}</p>
    <p><strong>Total Productos:</strong> {{ $devolucion->detalles->count() }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Código</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devolucion->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->esquema->nombre_producto }}</td>
                <td>{{ $detalle->producto->esquema->codigo_producto }}</td>
                <td style="text-align: center;">{{ $detalle->cantidad }}</td>
                <td style="text-align: right;"><span class="currency">Q{{ number_format($detalle->precio, 2) }}</span></td>
                <td style="text-align: right;"><span class="currency">Q{{ number_format($detalle->cantidad * $detalle->precio, 2) }}</span></td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4"><strong>TOTAL GENERAL</strong></td>
                <td style="text-align: right;"><strong><span class="currency">Q{{ number_format($devolucion->detalles->sum(function($item) { return $item->cantidad * $item->precio; }), 2) }}</span></strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
