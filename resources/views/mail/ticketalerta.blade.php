<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TICKET: {{ $data['fecha'] }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Source+Sans+3:wght@400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --azul-oscuro: #0d2b5e;
            --azul-medio: #1a4a8a;
            --azul-claro: #2d6cc0;
            --dorado: #c8922a;
            --dorado-claro: #e8b84b;
            --gris-claro: #f4f6fa;
            --gris-borde: #dde3ef;
            --texto-oscuro: #1a2340;
            --texto-medio: #4a5568;
            --blanco: #ffffff;
        }

        body {
            font-family: 'Source Sans 3', sans-serif;
            background: var(--gris-claro);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .ticket-wrapper {
            width: 100%;
            max-width: 620px;
        }

        /* ── TICKET CARD ── */
        .ticket {
            background: var(--blanco);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(13,43,94,.15), 0 1px 4px rgba(13,43,94,.08);
        }

        /* ── HEADER ── */
        .ticket-header {
            background: linear-gradient(135deg, var(--azul-oscuro) 0%, var(--azul-medio) 60%, var(--azul-claro) 100%);
            padding: 28px 32px 24px;
            position: relative;
            overflow: hidden;
        }

        .ticket-header::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 160px; height: 160px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
            transform: translate(40px, -60px);
        }

        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 40%;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--dorado), var(--dorado-claro), var(--dorado), transparent);
        }

        .header-inner {
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .logo-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--blanco);
            border: 3px solid var(--dorado);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,.25);
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }

        .header-text {
            color: var(--blanco);
        }

        .municipalidad {
            font-family: 'Oswald', sans-serif;
            font-size: 11px;
            font-weight: 400;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,.7);
            margin-bottom: 4px;
        }

        .nombre-alcaldia {
            font-family: 'Oswald', sans-serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: .5px;
            line-height: 1.15;
            color: var(--blanco);
        }

        .nombre-alcaldia span {
            color: var(--dorado-claro);
        }

        .sub-alcaldia {
            font-size: 12px;
            color: rgba(255,255,255,.6);
            margin-top: 4px;
            letter-spacing: .5px;
        }

        /* ── TICKET TITLE BAND ── */
        .ticket-title-band {
            background: var(--dorado);
            padding: 8px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ticket-label {
            font-family: 'Oswald', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--azul-oscuro);
        }

        .ticket-fecha {
            font-size: 12px;
            font-weight: 600;
            color: var(--azul-oscuro);
        }

        /* ── BODY ── */
        .ticket-body {
            padding: 28px 32px;
        }

        .field-group {
            margin-bottom: 20px;
        }

        .field-group:last-child {
            margin-bottom: 0;
        }

        .field-label {
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: var(--azul-claro);
            margin-bottom: 6px;
        }

        .field-value {
            font-size: 15px;
            font-weight: 500;
            color: var(--texto-oscuro);
            background: var(--gris-claro);
            border: 1px solid var(--gris-borde);
            border-left: 3px solid var(--azul-claro);
            border-radius: 3px;
            padding: 10px 14px;
            line-height: 1.5;
        }

        /* ── DIVIDER ── */
        .ticket-divider {
            border: none;
            border-top: 1px dashed var(--gris-borde);
            margin: 24px 0;
            position: relative;
        }

        .ticket-divider::before,
        .ticket-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 14px; height: 14px;
            background: var(--gris-claro);
            border: 1px solid var(--gris-borde);
            border-radius: 50%;
            transform: translateY(-50%);
        }

        .ticket-divider::before { left: -7px; }
        .ticket-divider::after  { right: -7px; }

        /* ── FOOTER ── */
        .ticket-footer {
            background: var(--azul-oscuro);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .footer-text {
            font-size: 11px;
            color: rgba(255,255,255,.5);
            letter-spacing: .4px;
        }

        .footer-badge {
            background: rgba(200,146,42,.2);
            border: 1px solid var(--dorado);
            border-radius: 3px;
            padding: 3px 10px;
            font-family: 'Oswald', sans-serif;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: var(--dorado-claro);
            text-transform: uppercase;
            white-space: nowrap;
        }

        /* ── PRINT ── */
        @media print {
            body { background: white; padding: 0; }
            .ticket { box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="ticket-wrapper">
    <div class="ticket">

        <!-- HEADER -->
        <div class="ticket-header">
            <div class="header-inner">

                <div class="header-text">
                    <h1 class="nombre-alcaldia">Alcaldía Santa Ana Norte</h1>
                    <p class="sub-alcaldia">Sistema de Gestión Municipal</p>
                </div>
            </div>
        </div>

        <!-- TITLE BAND -->
        <div class="ticket-title-band">
            <span class="ticket-label">&#9632; Ticket Generado</span>
            <span class="ticket-fecha">{{ $data['fecha'] }}</span>
        </div>

        <!-- BODY -->
        <div class="ticket-body">

            <div class="field-group">
                <p class="field-label">Unidad</p>
                <div class="field-value">{{ $data['unidad'] }}</div>
            </div>

            <hr class="ticket-divider">

            <div class="field-group">
                <p class="field-label">Mensaje</p>
                <div class="field-value">{{ $data['mensaje'] }}</div>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="ticket-footer">
            <span class="footer-text">Documento generado automáticamente &mdash; Alcaldía Santa Ana Norte</span>
        </div>

    </div>
</div>

</body>
</html>
