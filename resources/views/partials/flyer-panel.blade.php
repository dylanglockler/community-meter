@php $qrSize = $qrSize ?? 220; $compact = $compact ?? false; @endphp

<div class="flyer-panel flex flex-col items-center w-full {{ $compact ? 'p-3' : 'p-8' }}">

    {{-- Label --}}
    <div class="{{ $compact ? 'mb-2' : 'mb-4' }} text-center">
        <div class="inline-block bg-black text-white px-3 py-0.5 rounded {{ $compact ? 'text-[8px]' : 'text-xs' }} font-bold tracking-widest uppercase {{ $compact ? 'mb-1' : 'mb-3' }}">
            Community Survey / Encuesta Comunitaria
        </div>
        <h1 class="{{ $compact ? 'text-base' : 'text-3xl' }} font-extrabold text-gray-900 leading-tight {{ $compact ? 'mb-0.5' : 'mb-1' }}">
            Are you being overcharged for water?
        </h1>
        <p class="{{ $compact ? 'text-sm' : 'text-xl' }} font-bold text-gray-700">
            ¿Le están cobrando de más por el agua?
        </p>
    </div>

    {{-- QR Code + URL --}}
    <div class="flex flex-col items-center {{ $compact ? 'mb-2' : 'mb-5' }}">
        <div class="border-{{ $compact ? '2' : '4' }} border-black rounded-lg {{ $compact ? 'p-1' : 'p-2' }} bg-white">
            {!! QrCode::size($qrSize)->style('round')->generate(env('SURVEY_URL', 'https://survey.qr3.io')) !!}
        </div>
        <p class="{{ $compact ? 'text-base' : 'text-2xl' }} font-extrabold tracking-tight text-gray-900 {{ $compact ? 'mt-1' : 'mt-2' }}">survey.qr3.io</p>
        <p class="{{ $compact ? 'text-[9px]' : 'text-sm' }} text-gray-500">Scan or type · Escanee o escriba</p>
    </div>

    {{-- Bullets --}}
    <div class="grid grid-cols-2 gap-2 w-full {{ $compact ? 'mb-2' : 'mb-5' }}">
        <div class="border border-gray-200 rounded-lg {{ $compact ? 'p-2' : 'p-3' }}">
            <p class="{{ $compact ? 'text-[9px]' : 'text-xs' }} font-bold text-gray-900 {{ $compact ? 'mb-1' : 'mb-1.5' }}">Why this matters</p>
            <ul class="{{ $compact ? 'text-[8px]' : 'text-xs' }} text-gray-700 space-y-0.5">
                <li>• Water bills vary — is yours fair?</li>
                <li>• Together we can see the full picture</li>
                <li>• 2 minutes, 10 simple questions</li>
                <li>• Results shared back with residents</li>
            </ul>
        </div>
        <div class="border border-gray-200 rounded-lg {{ $compact ? 'p-2' : 'p-3' }}">
            <p class="{{ $compact ? 'text-[9px]' : 'text-xs' }} font-bold text-gray-900 {{ $compact ? 'mb-1' : 'mb-1.5' }}">Por qué importa</p>
            <ul class="{{ $compact ? 'text-[8px]' : 'text-xs' }} text-gray-700 space-y-0.5">
                <li>• ¿Es justa su factura de agua?</li>
                <li>• Juntos vemos el panorama completo</li>
                <li>• 2 minutos, 10 preguntas sencillas</li>
                <li>• Resultados compartidos con residentes</li>
            </ul>
        </div>
    </div>

    {{-- Anonymity --}}
    <div class="w-full border-2 border-gray-900 rounded-lg {{ $compact ? 'p-2' : 'p-4' }} text-center">
        <p class="{{ $compact ? 'text-[9px]' : 'text-sm' }} font-extrabold text-gray-900 {{ $compact ? 'mb-0.5' : 'mb-1' }}">100% ANONYMOUS · 100% ANÓNIMO</p>
        <p class="{{ $compact ? 'text-[8px]' : 'text-xs' }} text-gray-700 leading-snug">
            No name, unit number, or identifying info collected.<br>
            No se recopila nombre, unidad ni información identificable.
        </p>
    </div>

</div>
