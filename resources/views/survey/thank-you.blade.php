@extends('layouts.app')

@section('title', 'Thank You — Community Meter')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">

    <div class="mb-6 text-6xl">✓</div>

    <h1 class="text-3xl font-bold text-gray-900 mb-3">Thank you!</h1>
    <p class="text-xl text-gray-600 mb-8">Gracias</p>

    <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8 text-left">
        <p class="text-green-800 font-semibold mb-2">Your response has been recorded.</p>
        <p class="text-green-700 text-sm">Your submission is completely anonymous. We have no way to identify who submitted it — no IP address, no name, no unit number, nothing.</p>

        <hr class="my-4 border-green-200">

        <p class="text-green-800 font-semibold mb-2">Su respuesta ha sido registrada.</p>
        <p class="text-green-700 text-sm">Su envío es completamente anónimo. No tenemos forma de identificar quién lo envió — ninguna dirección IP, nombre, número de unidad, nada.</p>
    </div>

    @if($count > 1)
    <p class="text-gray-500 text-sm mb-8">
        You are one of <strong class="text-gray-700">{{ number_format($count) }}</strong> {{ $count === 1 ? 'person' : 'people' }} who have responded so far.
    </p>
    @endif

    <a href="{{ route('survey') }}"
        class="inline-block mt-2 text-blue-700 underline text-sm hover:text-blue-900 transition">
        Submit another response / Enviar otra respuesta
    </a>

</div>
@endsection
