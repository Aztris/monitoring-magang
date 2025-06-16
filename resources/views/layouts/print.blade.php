<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cetak Laporan')</title>
    <style>
        /* CSS Universal dari contoh Anda */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0; /* Padding akan diatur oleh @page */
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #2c3e50;
        }
        .header .subtitle {
            font-size: 14px;
            color: #7f8c8d;
        }
        .info-block {
            margin-bottom: 25px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            font-size: 14px;
        }
        .info-item strong {
            display: inline-block;
            width: 150px;
            color: #34495e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 13px;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 10px 12px;
            text-align: left;
            vertical-align: middle;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
            font-size: 12px;
            text-align: right;
            color: #7f8c8d;
        }
        .section-title {
            margin: 25px 0 15px 0;
            font-size: 16px;
            color: #2c3e50;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5px;
        }
        @media print {
            @page {
                margin: 15mm;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        @yield('header')
    </div>

    @hasSection('info-block')
        <div class="info-block">
            @yield('info-block')
        </div>
    @endif

    @hasSection('section-title')
         <h3 class="section-title">
            @yield('section-title')
        </h3>
    @endif

    @yield('main-content')

    @hasSection('signature-block')
        @yield('signature-block')
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }} oleh {{ auth()->user()->nama ?? 'System' }}
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        };
    </script>
</body>
</html>
