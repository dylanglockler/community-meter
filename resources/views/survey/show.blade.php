@extends('layouts.app')

@section('title', 'Community Water Billing Survey')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- Language Toggle --}}
    <div class="flex justify-end mb-4 gap-2" id="lang-toggle">
        <button onclick="setLang('en')" id="btn-en"
            class="px-4 py-1.5 rounded-full text-sm font-semibold border-2 border-blue-700 bg-blue-700 text-white transition">
            English
        </button>
        <button onclick="setLang('es')" id="btn-es"
            class="px-4 py-1.5 rounded-full text-sm font-semibold border-2 border-blue-700 text-blue-700 bg-white transition">
            Español
        </button>
    </div>

    {{-- Anonymity Notice --}}
    <div class="bg-green-50 border border-green-300 rounded-xl p-5 mb-4">
        <div class="lang-en">
            <p class="font-semibold text-green-800 mb-1">Your Privacy is Protected</p>
            <p class="text-green-700 text-sm leading-relaxed">This survey is completely anonymous. We do not collect your name, address, unit number, or any information that could identify you. Your answers cannot be traced back to you.</p>
        </div>
        <div class="lang-es hidden">
            <p class="font-semibold text-green-800 mb-1">Su privacidad está protegida</p>
            <p class="text-green-700 text-sm leading-relaxed">Esta encuesta es completamente anónima. No recopilamos su nombre, dirección, número de unidad ni ninguna información que pueda identificarle. Sus respuestas no pueden ser rastreadas hasta usted.</p>
        </div>
    </div>

    {{-- Independence Disclosure --}}
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-8">
        <div class="lang-en">
            <p class="text-gray-600 text-sm leading-relaxed">This survey is being collected by an independent reviewer in an effort to protect residents' rights and is not associated with Medford Estates, the property management, or any individual resident.</p>
        </div>
        <div class="lang-es hidden">
            <p class="text-gray-600 text-sm leading-relaxed">Esta encuesta está siendo recopilada por un revisor independiente con el fin de proteger los derechos de los residentes y no está asociada con Medford Estates, la administración de la propiedad ni ningún residente individual.</p>
        </div>
    </div>

    {{-- Headline --}}
    <div class="mb-8 text-center">
        <div class="lang-en">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Water Billing Survey</h1>
            <p class="text-gray-600 text-sm">Help your community understand how water charges are being applied. This takes about 2 minutes.</p>
        </div>
        <div class="lang-es hidden">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Encuesta sobre facturación de agua</h1>
            <p class="text-gray-600 text-sm">Ayude a su comunidad a entender cómo se aplican los cargos de agua. Esto toma aproximadamente 2 minutos.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-300 rounded-xl p-4 mb-6">
        <ul class="text-red-700 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('survey.store') }}" class="space-y-8">
        @csrf

        {{-- Q1: Water charge amount --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="lang-en">
                <p class="font-semibold text-gray-900 mb-3">1. How much are you charged for water each month?</p>
            </div>
            <div class="lang-es hidden">
                <p class="font-semibold text-gray-900 mb-3">1. ¿Cuánto le cobran por el agua cada mes?</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach([
                    'Under $25' => ['en' => 'Under $25', 'es' => 'Menos de $25'],
                    '$25–$49' => ['en' => '$25–$49', 'es' => '$25–$49'],
                    '$50–$74' => ['en' => '$50–$74', 'es' => '$50–$74'],
                    '$75–$99' => ['en' => '$75–$99', 'es' => '$75–$99'],
                    '$100–$149' => ['en' => '$100–$149', 'es' => '$100–$149'],
                    '$150–$199' => ['en' => '$150–$199', 'es' => '$150–$199'],
                    '$200–$249' => ['en' => '$200–$249', 'es' => '$200–$249'],
                    '$250–$299' => ['en' => '$250–$299', 'es' => '$250–$299'],
                    '$300–$349' => ['en' => '$300–$349', 'es' => '$300–$349'],
                    '$350–$399' => ['en' => '$350–$399', 'es' => '$350–$399'],
                    '$400 or more' => ['en' => '$400 or more', 'es' => '$400 o más'],
                    'I don\'t pay separately for water' => ['en' => 'I don\'t pay separately for water', 'es' => 'No pago el agua por separado'],
                ] as $value => $labels)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400">
                    <input type="radio" name="water_charge_range" value="{{ $value }}" class="text-blue-600" {{ old('water_charge_range') === $value ? 'checked' : '' }}>
                    <span class="lang-en text-sm">{{ $labels['en'] }}</span>
                    <span class="lang-es hidden text-sm">{{ $labels['es'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Q2: Household size --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="lang-en">
                <p class="font-semibold text-gray-900 mb-3">2. How many people live in your home?</p>
            </div>
            <div class="lang-es hidden">
                <p class="font-semibold text-gray-900 mb-3">2. ¿Cuántas personas viven en su hogar?</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @foreach(['1', '2', '3', '4', '5+'] as $size)
                <label class="flex items-center gap-2 px-5 py-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400 font-medium">
                    <input type="radio" name="household_size" value="{{ $size }}" class="text-blue-600" {{ old('household_size') === $size ? 'checked' : '' }}>
                    {{ $size }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Q3: Separate charge --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="lang-en">
                <p class="font-semibold text-gray-900 mb-3">3. Do you receive a separate water charge from the park each month?</p>
            </div>
            <div class="lang-es hidden">
                <p class="font-semibold text-gray-900 mb-3">3. ¿Recibe un cargo de agua separado del parque cada mes?</p>
            </div>
            <div class="space-y-2">
                @foreach([
                    'Yes, separate charge' => ['en' => 'Yes, it\'s a separate charge', 'es' => 'Sí, es un cargo separado'],
                    'No, included in rent' => ['en' => 'No, water is included in my rent', 'es' => 'No, el agua está incluida en mi renta'],
                    'Not sure' => ['en' => 'Not sure', 'es' => 'No estoy seguro/a'],
                ] as $value => $labels)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400">
                    <input type="radio" name="separate_charge" value="{{ $value }}" class="text-blue-600" {{ old('separate_charge') === $value ? 'checked' : '' }}>
                    <span class="lang-en text-sm">{{ $labels['en'] }}</span>
                    <span class="lang-es hidden text-sm">{{ $labels['es'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Q4: Charge calculation --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="lang-en">
                <p class="font-semibold text-gray-900 mb-3">4. Do you know how your water charge is calculated?</p>
            </div>
            <div class="lang-es hidden">
                <p class="font-semibold text-gray-900 mb-3">4. ¿Sabe cómo se calcula su cargo de agua?</p>
            </div>
            <div class="space-y-2">
                @foreach([
                    'Meter for my home only' => ['en' => 'Based on a meter for my home only', 'es' => 'Basado en un medidor solo para mi hogar'],
                    'Split among all residents' => ['en' => 'Split among all residents', 'es' => 'Dividido entre todos los residentes'],
                    'Flat fee' => ['en' => 'Flat fee', 'es' => 'Tarifa fija'],
                    'I don\'t know' => ['en' => 'I don\'t know', 'es' => 'No lo sé'],
                ] as $value => $labels)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400">
                    <input type="radio" name="charge_calculation" value="{{ $value }}" class="text-blue-600" {{ old('charge_calculation') === $value ? 'checked' : '' }}>
                    <span class="lang-en text-sm">{{ $labels['en'] }}</span>
                    <span class="lang-es hidden text-sm">{{ $labels['es'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Q5: Charge increased --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="lang-en">
                <p class="font-semibold text-gray-900 mb-3">5. Has your water charge increased in the last 12 months?</p>
            </div>
            <div class="lang-es hidden">
                <p class="font-semibold text-gray-900 mb-3">5. ¿Ha aumentado su cargo de agua en los últimos 12 meses?</p>
            </div>
            <div class="space-y-2">
                @foreach([
                    'Yes, significantly' => ['en' => 'Yes, significantly', 'es' => 'Sí, significativamente'],
                    'Yes, somewhat' => ['en' => 'Yes, somewhat', 'es' => 'Sí, algo'],
                    'No' => ['en' => 'No', 'es' => 'No'],
                    'Not sure' => ['en' => 'Not sure', 'es' => 'No estoy seguro/a'],
                ] as $value => $labels)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400">
                    <input type="radio" name="charge_increased" value="{{ $value }}" class="text-blue-600" {{ old('charge_increased') === $value ? 'checked' : '' }}>
                    <span class="lang-en text-sm">{{ $labels['en'] }}</span>
                    <span class="lang-es hidden text-sm">{{ $labels['es'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Q6: Shown records --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="lang-en">
                <p class="font-semibold text-gray-900 mb-3">6. Have you ever been shown the park's water bill or records explaining the charge?</p>
            </div>
            <div class="lang-es hidden">
                <p class="font-semibold text-gray-900 mb-3">6. ¿Alguna vez le han mostrado la factura de agua del parque o registros que expliquen el cargo?</p>
            </div>
            <div class="space-y-2">
                @foreach([
                    'Yes' => ['en' => 'Yes', 'es' => 'Sí'],
                    'No' => ['en' => 'No', 'es' => 'No'],
                    'Requested and denied' => ['en' => 'I requested them and was denied', 'es' => 'Los solicité y me los negaron'],
                    'Never asked' => ['en' => 'I\'ve never asked', 'es' => 'Nunca lo he pedido'],
                ] as $value => $labels)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400">
                    <input type="radio" name="shown_records" value="{{ $value }}" class="text-blue-600" {{ old('shown_records') === $value ? 'checked' : '' }}>
                    <span class="lang-en text-sm">{{ $labels['en'] }}</span>
                    <span class="lang-es hidden text-sm">{{ $labels['es'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Q7: Home ownership --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="lang-en">
                <p class="font-semibold text-gray-900 mb-3">7. Do you own your home, or does the park own it?</p>
            </div>
            <div class="lang-es hidden">
                <p class="font-semibold text-gray-900 mb-3">7. ¿Es dueño/a de su hogar, o es propiedad del parque?</p>
            </div>
            <div class="space-y-2">
                @foreach([
                    'I own my home' => ['en' => 'I own my home', 'es' => 'Soy dueño/a de mi hogar'],
                    'Park owns my home' => ['en' => 'The park owns my home', 'es' => 'El parque es dueño de mi hogar'],
                    'Other' => ['en' => 'Other', 'es' => 'Otro'],
                    'Not sure' => ['en' => 'Not sure', 'es' => 'No estoy seguro/a'],
                ] as $value => $labels)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400">
                    <input type="radio" name="home_ownership" value="{{ $value }}" class="text-blue-600" {{ old('home_ownership') === $value ? 'checked' : '' }}>
                    <span class="lang-en text-sm">{{ $labels['en'] }}</span>
                    <span class="lang-es hidden text-sm">{{ $labels['es'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Q8: Home age --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="lang-en">
                <p class="font-semibold text-gray-900 mb-3">8. Is your home newer or older?</p>
            </div>
            <div class="lang-es hidden">
                <p class="font-semibold text-gray-900 mb-3">8. ¿Su hogar es nuevo o antiguo?</p>
            </div>
            <div class="space-y-2">
                @foreach([
                    'Newer' => ['en' => 'Newer (built roughly in the last 5–7 years)', 'es' => 'Más nuevo (construido aproximadamente en los últimos 5–7 años)'],
                    'Older' => ['en' => 'Older home', 'es' => 'Hogar más antiguo'],
                    'Not sure' => ['en' => 'Not sure', 'es' => 'No estoy seguro/a'],
                ] as $value => $labels)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400">
                    <input type="radio" name="home_age" value="{{ $value }}" class="text-blue-600" {{ old('home_age') === $value ? 'checked' : '' }}>
                    <span class="lang-en text-sm">{{ $labels['en'] }}</span>
                    <span class="lang-es hidden text-sm">{{ $labels['es'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Q9: Residency duration --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="lang-en">
                <p class="font-semibold text-gray-900 mb-3">9. How long have you lived in the park?</p>
            </div>
            <div class="lang-es hidden">
                <p class="font-semibold text-gray-900 mb-3">9. ¿Cuánto tiempo lleva viviendo en el parque?</p>
            </div>
            <div class="space-y-2">
                @foreach([
                    'Less than 1 year' => ['en' => 'Less than 1 year', 'es' => 'Menos de 1 año'],
                    '1–3 years' => ['en' => '1–3 years', 'es' => '1–3 años'],
                    '3–5 years' => ['en' => '3–5 years', 'es' => '3–5 años'],
                    'More than 5 years' => ['en' => 'More than 5 years', 'es' => 'Más de 5 años'],
                ] as $value => $labels)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400">
                    <input type="radio" name="residency_duration" value="{{ $value }}" class="text-blue-600" {{ old('residency_duration') === $value ? 'checked' : '' }}>
                    <span class="lang-en text-sm">{{ $labels['en'] }}</span>
                    <span class="lang-es hidden text-sm">{{ $labels['es'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Q10: Optional comments --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="lang-en">
                <p class="font-semibold text-gray-900 mb-1">10. Anything else you'd like to share about water billing? <span class="font-normal text-gray-400">(optional)</span></p>
            </div>
            <div class="lang-es hidden">
                <p class="font-semibold text-gray-900 mb-1">10. ¿Hay algo más que quiera compartir sobre la facturación del agua? <span class="font-normal text-gray-400">(opcional)</span></p>
            </div>
            <textarea name="additional_comments" rows="4"
                class="mt-3 w-full rounded-lg border border-gray-200 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
                placeholder="Your comments...">{{ old('additional_comments') }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="text-center pb-4">
            <button type="submit"
                class="w-full sm:w-auto px-10 py-4 bg-blue-700 hover:bg-blue-800 text-white font-bold text-lg rounded-xl shadow-md transition">
                <span class="lang-en">Submit Survey</span>
                <span class="lang-es hidden">Enviar encuesta</span>
            </button>
        </div>

    </form>

    <p class="text-center text-xs text-gray-400 mt-6 mb-8">
        <span class="lang-en">Your response is completely anonymous and cannot be traced back to you.</span>
        <span class="lang-es hidden">Su respuesta es completamente anónima y no puede ser rastreada hasta usted.</span>
    </p>

</div>

<script>
function setLang(lang) {
    document.querySelectorAll('.lang-en').forEach(el => el.classList.toggle('hidden', lang !== 'en'));
    document.querySelectorAll('.lang-es').forEach(el => el.classList.toggle('hidden', lang !== 'es'));
    document.getElementById('btn-en').classList.toggle('bg-blue-700', lang === 'en');
    document.getElementById('btn-en').classList.toggle('text-white', lang === 'en');
    document.getElementById('btn-en').classList.toggle('text-blue-700', lang !== 'en');
    document.getElementById('btn-en').classList.toggle('bg-white', lang !== 'en');
    document.getElementById('btn-es').classList.toggle('bg-blue-700', lang === 'es');
    document.getElementById('btn-es').classList.toggle('text-white', lang === 'es');
    document.getElementById('btn-es').classList.toggle('text-blue-700', lang !== 'es');
    document.getElementById('btn-es').classList.toggle('bg-white', lang !== 'es');
    localStorage.setItem('cm_lang', lang);
}

// Restore language preference
const saved = localStorage.getItem('cm_lang');
if (saved === 'es') setLang('es');
</script>
@endsection
