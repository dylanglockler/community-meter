<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Water Billing Survey — Flyer (2-up)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { size: letter portrait; margin: 0.2in; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .sheet { display: flex; flex-direction: column; height: 100vh; gap: 0; }
            .panel-wrap { flex: 1; display: flex; align-items: stretch; overflow: hidden; }
            .cut-line { border: none; border-top: 1px dashed #aaa; margin: 0; }
        }
        body { background: #f3f4f6; }
        .sheet { background: white; }
    </style>
</head>
<body class="p-4">

    <div class="no-print mb-5 text-center">
        <button onclick="window.print()" class="px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg shadow hover:bg-blue-800 transition text-sm">
            Print 2-up
        </button>
        <p class="text-gray-400 text-xs mt-2">2 flyers per page · Cut along the dashed line</p>
        <div class="mt-3 flex justify-center gap-4 text-xs">
            <a href="{{ route('flyer') }}" class="text-blue-600 underline">1-up version</a>
            <a href="{{ route('flyer.4up') }}" class="text-blue-600 underline">4-up version</a>
        </div>
    </div>

    <div class="sheet max-w-[680px] mx-auto rounded-xl shadow-xl border border-gray-200 overflow-hidden">

        <div class="panel-wrap">
            @include('partials.flyer-panel', ['qrSize' => 150, 'compact' => true])
        </div>

        <hr class="cut-line border-dashed border-gray-300">

        <div class="panel-wrap">
            @include('partials.flyer-panel', ['qrSize' => 150, 'compact' => true])
        </div>

    </div>

</body>
</html>
