@extends('layouts.app')

@section('title', 'Admin Dashboard — Community Meter')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Community Meter Admin</h1>
            <p class="text-gray-500 text-sm mt-1">{{ number_format($total) }} total {{ $total === 1 ? 'response' : 'responses' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.export') }}"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                Export CSV
            </a>
            <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg transition">
                    Logout
                </button>
            </form>
        </div>
    </div>

    @if($total > 0)

    {{-- Key Findings --}}
    <h2 class="text-base font-bold text-gray-500 uppercase tracking-wider mb-3">Key Findings</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 mb-8">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-3xl font-extrabold text-blue-700">{{ $total }}</p>
            <p class="text-xs text-gray-500 mt-1 leading-tight">Total<br>Responses</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-3xl font-extrabold {{ $insights['high_charge_pct'] >= 50 ? 'text-red-600' : 'text-gray-800' }}">
                {{ $insights['high_charge_pct'] }}%
            </p>
            <p class="text-xs text-gray-500 mt-1 leading-tight">Pay $100+<br>per month</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-3xl font-extrabold {{ $insights['increased_pct'] >= 50 ? 'text-red-600' : 'text-gray-800' }}">
                {{ $insights['increased_pct'] }}%
            </p>
            <p class="text-xs text-gray-500 mt-1 leading-tight">Charge<br>increased</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-3xl font-extrabold {{ $insights['unknown_calc_pct'] >= 40 ? 'text-amber-600' : 'text-gray-800' }}">
                {{ $insights['unknown_calc_pct'] }}%
            </p>
            <p class="text-xs text-gray-500 mt-1 leading-tight">Don't know<br>how calculated</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-3xl font-extrabold {{ $insights['denied_count'] > 0 ? 'text-red-600' : 'text-gray-800' }}">
                {{ $insights['denied_count'] }}
            </p>
            <p class="text-xs text-gray-500 mt-1 leading-tight">Denied<br>records</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-3xl font-extrabold text-gray-800">{{ $insights['separate_pct'] }}%</p>
            <p class="text-xs text-gray-500 mt-1 leading-tight">Separate<br>water bill</p>
        </div>

    </div>

    {{-- Narrative Summary --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-8">
        <h3 class="font-bold text-blue-900 mb-2">Summary</h3>
        <p class="text-blue-800 text-sm leading-relaxed">
            Of {{ $total }} anonymous {{ $total === 1 ? 'response' : 'responses' }} collected
            @if($insights['first_response'])
                between {{ $insights['first_response']->format('M j') }} and {{ $insights['latest_response']->format('M j, Y') }}
            @endif
            :
            <strong>{{ $insights['high_charge_pct'] }}%</strong> report paying $100 or more per month for water,
            with the most common range being <strong>{{ $insights['top_charge'] }}</strong>.
            <strong>{{ $insights['increased_pct'] }}%</strong> say their charge has increased in the last 12 months.
            <strong>{{ $insights['unknown_calc_pct'] }}%</strong> don't know how their charge is calculated,
            and <strong>{{ $insights['denied_count'] }}</strong> {{ $insights['denied_count'] === 1 ? 'resident' : 'residents' }}
            requested records and were denied.
        </p>
    </div>

    @else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center text-gray-400 mb-8">
        No responses yet. Share the survey link to get started.
    </div>
    @endif

    {{-- Flyer Downloads --}}
    <h2 class="text-base font-bold text-gray-500 uppercase tracking-wider mb-3">Flyers</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
        <a href="{{ route('flyer') }}" target="_blank"
            class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:border-blue-400 hover:shadow-md transition group">
            <div class="text-2xl mb-2">🗒</div>
            <p class="font-semibold text-gray-900 group-hover:text-blue-700">1-up Flyer</p>
            <p class="text-sm text-gray-500 mt-1">Full page · Best quality · Post on bulletin boards</p>
        </a>
        <a href="{{ route('flyer.2up') }}" target="_blank"
            class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:border-blue-400 hover:shadow-md transition group">
            <div class="text-2xl mb-2">📄</div>
            <p class="font-semibold text-gray-900 group-hover:text-blue-700">2-up Flyer</p>
            <p class="text-sm text-gray-500 mt-1">2 per page · Cut in half · Hand out door-to-door</p>
        </a>
        <a href="{{ route('flyer.4up') }}" target="_blank"
            class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:border-blue-400 hover:shadow-md transition group">
            <div class="text-2xl mb-2">🗂</div>
            <p class="font-semibold text-gray-900 group-hover:text-blue-700">4-up Flyer</p>
            <p class="text-sm text-gray-500 mt-1">4 per page · Most paper-efficient · Quick distribution</p>
        </a>
    </div>

    @if($total > 0)

    {{-- Per-field Breakdown --}}
    <h2 class="text-base font-bold text-gray-500 uppercase tracking-wider mb-3">Response Breakdown</h2>
    @php
    $statLabels = [
        'water_charge_range' => 'Monthly Water Charge',
        'household_size'     => 'Household Size',
        'separate_charge'    => 'Separate Water Charge',
        'charge_calculation' => 'Charge Calculation Method',
        'charge_increased'   => 'Charge Increased (12 Months)',
        'shown_records'      => 'Shown Park Water Records',
        'home_ownership'     => 'Home Ownership',
        'home_age'           => 'Home Age',
        'residency_duration' => 'Length of Residency',
    ];
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-10">
        @foreach($stats as $key => $counts)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 text-sm mb-3">{{ $statLabels[$key] }}</h3>
            <ul class="space-y-2">
                @foreach($counts as $label => $count)
                <li class="text-sm">
                    <div class="flex justify-between mb-0.5">
                        <span class="text-gray-700 truncate max-w-[180px]" title="{{ $label }}">{{ $label }}</span>
                        <span class="text-gray-500 font-medium ml-2 shrink-0">{{ $count }} <span class="text-gray-300">({{ $total > 0 ? round($count/$total*100) : 0 }}%)</span></span>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full transition-all"
                             style="width: {{ $total > 0 ? round(($count / $total) * 100) : 0 }}%"></div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>

    {{-- Response Table --}}
    <h2 class="text-base font-bold text-gray-500 uppercase tracking-wider mb-3">All Responses</h2>
    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm mb-8">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">#</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Date</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Water Charge</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Household</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Separate Bill</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Calculation</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Increased</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Shown Records</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Ownership</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Home Age</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Residency</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Comments</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($responses as $i => $r)
                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/50' }} hover:bg-blue-50/30">
                    <td class="px-4 py-3 text-gray-400">{{ $total - $i }}</td>
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $r->submitted_at->format('M j, Y') }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800 whitespace-nowrap">{{ $r->water_charge_range }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $r->household_size }}</td>
                    <td class="px-4 py-3 text-gray-800 whitespace-nowrap">{{ $r->separate_charge }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $r->charge_calculation }}</td>
                    <td class="px-4 py-3 text-gray-800 whitespace-nowrap">
                        <span class="{{ in_array($r->charge_increased, ['Yes, significantly', 'Yes, somewhat']) ? 'text-red-600 font-medium' : '' }}">
                            {{ $r->charge_increased }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-800 whitespace-nowrap">
                        <span class="{{ $r->shown_records === 'Requested and denied' ? 'text-red-600 font-medium' : '' }}">
                            {{ $r->shown_records }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-800 whitespace-nowrap">{{ $r->home_ownership }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $r->home_age }}</td>
                    <td class="px-4 py-3 text-gray-800 whitespace-nowrap">{{ $r->residency_duration }}</td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        @if($r->additional_comments)
                            <span class="block truncate" title="{{ $r->additional_comments }}">{{ $r->additional_comments }}</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @endif

</div>
@endsection
