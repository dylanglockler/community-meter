<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Water Billing Survey — Flyer (1-up)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white; }
            .no-print { display: none !important; }
            .page { box-shadow: none !important; margin: 0 !important; border: none !important; max-width: 100% !important; }
        }
        body { background: #f3f4f6; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6">

    <div class="no-print mb-6 text-center">
        <button onclick="window.print()" class="px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg shadow hover:bg-blue-800 transition text-sm">
            Print Flyer
        </button>
        <p class="text-gray-400 text-xs mt-2">1 flyer per page · Letter size · Portrait</p>
        <div class="mt-3 flex justify-center gap-4 text-xs">
            <a href="{{ route('flyer.2up') }}" class="text-blue-600 underline">2-up version</a>
            <a href="{{ route('flyer.4up') }}" class="text-blue-600 underline">4-up version</a>
        </div>
    </div>

    <div class="page bg-white w-full max-w-[680px] min-h-[900px] rounded-2xl shadow-xl border border-gray-200 flex flex-col items-center">
        @include('partials.flyer-panel', ['qrSize' => 220, 'compact' => false])
    </div>

</body>
</html>
