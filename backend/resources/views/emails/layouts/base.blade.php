<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? 'Notification Al-Amine RDV' }}</title>
    <style>
        :root {
            color-scheme: light;
        }
        body {
            margin: 0;
            font-family: 'Nunito', 'Segoe UI', Tahoma, Geneva, sans-serif;
            background: #eef2ff;
            color: #0f172a;
            line-height: 1.6;
        }
        a {
            color: #2563eb;
            text-decoration: none;
        }
        .wrapper {
            width: 100%;
            padding: 32px 16px;
        }
        .container {
            max-width: 680px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.12);
        }
        .header {
            background: linear-gradient(135deg, #0f172a, #1d4ed8, #3b82f6);
            padding: 36px 40px;
            color: #f8fafc;
        }
        .header-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-logo img {
            height: 60px;
            border-radius: 12px;
        }
        .header-tagline {
            margin-top: 6px;
            font-size: 13px;
            opacity: 0.88;
        }
        .content {
            padding: 36px 40px;
        }
        .card {
            background: #f8fafc;
            border-radius: 18px;
            padding: 28px;
            border: 1px solid rgba(59, 130, 246, 0.16);
        }
        .footer {
            padding: 0 40px 36px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .cta-button {
            display: inline-block;
            padding: 14px 28px;
            border-radius: 9999px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.35);
        }
        .rows {
            margin-top: 16px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 6px 0;
            padding: 10px 14px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid rgba(59,130,246,0.12);
        }
        .row-label {
            color: #475569;
            font-weight: 600;
        }
        .row-value {
            color: #0f172a;
            font-weight: 600;
        }
        .subcopy {
            margin-top: 24px;
            font-size: 12px;
            color: #64748b;
        }
        @media (max-width: 600px) {
            .header,
            .content,
            .footer {
                padding: 24px;
            }
            .card {
                padding: 22px;
            }
            .row {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="header-logo">
                    <img src="{{ $message->embed(public_path('photos/logoalmine.png')) }}" alt="Al-Amine RDV">
                </div>
                <div class="header-tagline">Votre partenaire santé au Sénégal</div>
            </div>
            <div class="content">
                <div class="card">
                    @yield('content')
                </div>
                @hasSection('subcopy')
                    <div class="subcopy">
                        @yield('subcopy')
                    </div>
                @endif
            </div>
            <div class="footer">
                © {{ date('Y') }} Al-Amine RDV · Sacré-Cœur III, Dakar · +221 33 123 45 67
            </div>
        </div>
    </div>
</body>
</html>
