<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Water Billing Survey — Flyer (4-up)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { size: letter portrait; margin: 0.15in; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .grid-4 { display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; height: 100vh; gap: 0; }
            .panel-wrap { overflow: hidden; border: 0.5px dashed #ccc; }
        }
        body { background: #f3f4f6; }
        .grid-4 { background: white; }
    </style>
</head>
<body class="p-4">

    <div class="no-print mb-5 text-center">
        <button onclick="window.print()" class="px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg shadow hover:bg-blue-800 transition text-sm">
            Print 4-up
        </button>
        <p class="text-gray-400 text-xs mt-2">4 flyers per page · Cut along the dashed lines</p>
        <div class="mt-3 flex justify-center gap-4 text-xs">
            <a href="{{ route('flyer') }}" class="text-blue-600 underline">1-up version</a>
            <a href="{{ route('flyer.2up') }}" class="text-blue-600 underline">2-up version</a>
        </div>
    </div>

    <div class="grid-4 max-w-[680px] mx-auto rounded-xl shadow-xl overflow-hidden border border-gray-200"
         style="display: grid; grid-template-columns: 1fr 1fr;">

        <div class="panel-wrap border border-dashed border-gray-300">
            @include('partials.flyer-panel', ['qrSize' => 95, 'compact' => true])
        </div>
        <div class="panel-wrap border border-dashed border-gray-300">
            @include('partials.flyer-panel', ['qrSize' => 95, 'compact' => true])
        </div>
        <div class="panel-wrap border border-dashed border-gray-300">
            @include('partials.flyer-panel', ['qrSize' => 95, 'compact' => true])
        </div>
        <div class="panel-wrap border border-dashed border-gray-300">
            @include('partials.flyer-panel', ['qrSize' => 95, 'compact' => true])
        </div>

    </div>

</body>
</html>
