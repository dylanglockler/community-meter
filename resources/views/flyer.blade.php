<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Water Billing Survey — Flyer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page { box-shadow: none !important; margin: 0 !important; border: none !important; }
        }
        body { background: #f3f4f6; }
        .page { background: white; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6">

    {{-- Print Button (hidden on print) --}}
    <div class="no-print mb-6 text-center">
        <button onclick="window.print()"
            class="px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg shadow hover:bg-blue-800 transition text-sm">
            Print Flyer
        </button>
        <p class="text-gray-400 text-xs mt-2">Use your browser's print dialog. Recommended: Letter size, scale to fit.</p>
    </div>

    {{-- Flyer Page --}}
    <div class="page w-full max-w-[680px] min-h-[900px] rounded-2xl shadow-xl border border-gray-200 p-10 flex flex-col items-center">

        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="inline-block bg-black text-white px-4 py-1 rounded text-xs font-bold tracking-widest uppercase mb-4">
                Community Survey / Encuesta Comunitaria
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 leading-tight mb-1">
                Are you being overcharged<br>for water?
            </h1>
            <p class="text-xl font-bold text-gray-700 mt-1">
                ¿Le están cobrando de más<br>por el agua?
            </p>
        </div>

        {{-- QR Code --}}
        <div class="flex flex-col items-center mb-6">
            <div class="border-4 border-black rounded-xl p-2 bg-white">
                {!! QrCode::size(220)->style('round')->generate(env('SURVEY_URL', 'https://survey.qr3.io')) !!}
            </div>
            <p class="mt-3 text-2xl font-extrabold tracking-tight text-gray-900">survey.qr3.io</p>
            <p class="text-sm text-gray-500 mt-0.5">Scan or type in any browser / Escanee o escriba en cualquier navegador</p>
        </div>

        {{-- Bullet Points --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full mb-8">

            <div class="border border-gray-200 rounded-xl p-4">
                <h2 class="font-bold text-gray-900 text-sm mb-2">Why this matters</h2>
                <ul class="space-y-1.5 text-sm text-gray-700">
                    <li class="flex gap-2"><span class="font-bold">•</span> Water bills vary widely — is yours fair?</li>
                    <li class="flex gap-2"><span class="font-bold">•</span> Together, we can see the full picture</li>
                    <li class="flex gap-2"><span class="font-bold">•</span> Just 2 minutes, 10 simple questions</li>
                    <li class="flex gap-2"><span class="font-bold">•</span> Results shared back with residents</li>
                </ul>
            </div>

            <div class="border border-gray-200 rounded-xl p-4">
                <h2 class="font-bold text-gray-900 text-sm mb-2">Por qué importa</h2>
                <ul class="space-y-1.5 text-sm text-gray-700">
                    <li class="flex gap-2"><span class="font-bold">•</span> Las facturas de agua varían — ¿es justa la suya?</li>
                    <li class="flex gap-2"><span class="font-bold">•</span> Juntos podemos ver el panorama completo</li>
                    <li class="flex gap-2"><span class="font-bold">•</span> Solo 2 minutos, 10 preguntas sencillas</li>
                    <li class="flex gap-2"><span class="font-bold">•</span> Resultados compartidos con los residentes</li>
                </ul>
            </div>

        </div>

        {{-- Anonymity Box --}}
        <div class="w-full border-2 border-gray-900 rounded-xl p-5 mb-4 text-center">
            <p class="font-extrabold text-gray-900 text-base mb-1">100% ANONYMOUS / 100% ANÓNIMO</p>
            <p class="text-sm text-gray-700 leading-relaxed">
                We do not collect your name, unit number, or any identifying information.<br>
                <span class="font-medium">No recopilamos su nombre, número de unidad ni ninguna información que lo identifique.</span>
            </p>
        </div>

        <p class="text-xs text-gray-400 text-center mt-auto pt-4">
            Resident-organized community survey &nbsp;·&nbsp; survey.qr3.io
        </p>

    </div>

</body>
</html>
