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
            @php $pressurePct = $displacement['total_answered'] > 0 ? round($displacement['total_with_pressure'] / $displacement['total_answered'] * 100) : 0; @endphp
            <p class="text-3xl font-extrabold {{ $pressurePct >= 20 ? 'text-red-600' : 'text-gray-800' }}">
                {{ $pressurePct }}%
            </p>
            <p class="text-xs text-gray-500 mt-1 leading-tight">Report<br>displacement pressure</p>
        </div>

    </div>

    {{-- Displacement Signals --}}
    @if($displacement['total_answered'] > 0)
    <h2 class="text-base font-bold text-gray-500 uppercase tracking-wider mb-3">Displacement Signals</h2>
    <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="font-semibold text-red-900 mb-3 text-sm">Reported pressure to sell, move, or leave</p>
                <p class="text-3xl font-extrabold text-red-700 mb-1">
                    {{ $displacement['total_with_pressure'] }}
                    <span class="text-base font-normal text-red-500">/ {{ $displacement['total_answered'] }} answered</span>
                </p>
                <div class="mt-4 space-y-2">
                    @foreach($displacement['by_home_age'] as $age => $data)
                    @if($data['total'] > 0)
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 mb-0.5">
                            <span>{{ $age }} homes</span>
                            <span>{{ $data['pressure'] }} / {{ $data['total'] }}
                                ({{ round($data['pressure'] / $data['total'] * 100) }}%)</span>
                        </div>
                        <div class="h-2 bg-red-100 rounded-full overflow-hidden">
                            <div class="h-full bg-red-500 rounded-full"
                                 style="width: {{ round($data['pressure'] / $data['total'] * 100) }}%"></div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            <div>
                <p class="font-semibold text-red-900 mb-3 text-sm">Type of pressure reported</p>
                <ul class="space-y-1.5">
                    @foreach($displacement['by_type'] as $type => $count)
                    @if($count > 0)
                    <li class="flex items-center justify-between text-xs">
                        <span class="text-gray-700 mr-2">{{ $type }}</span>
                        <span class="font-semibold text-red-700 shrink-0">{{ $count }}</span>
                    </li>
                    @endif
                    @endforeach
                    @if(array_sum($displacement['by_type']) === 0)
                    <li class="text-xs text-gray-400 italic">None yet</li>
                    @endif
                </ul>
                <p class="font-semibold text-red-900 mt-4 mb-2 text-sm">Believe charges are used to push them out</p>
                <ul class="space-y-1.5">
                    @foreach($displacement['charges_to_push'] as $label => $count)
                    @if($count > 0)
                    <li class="flex items-center justify-between text-xs">
                        <span class="text-gray-700">{{ $label }}</span>
                        <span class="font-semibold shrink-0 {{ $label === 'Yes' ? 'text-red-700' : 'text-gray-600' }}">{{ $count }}</span>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

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
            @if($displacement['total_answered'] > 0)
                Of {{ $displacement['total_answered'] }} {{ $displacement['total_answered'] === 1 ? 'respondent' : 'respondents' }} who answered the displacement questions,
                <strong>{{ $displacement['total_with_pressure'] }}</strong> reported some form of pressure to leave.
            @endif
        </p>
        <p class="text-blue-600 text-xs mt-2 italic">Note: with small sample sizes these are directional indicators, not statistical proof. Patterns here suggest areas for further investigation rather than definitive conclusions.</p>
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
        'water_charge_range'  => 'Monthly Water Charge',
        'household_size'      => 'Household Size',
        'separate_charge'     => 'Separate Water Charge',
        'charge_calculation'  => 'Charge Calculation Method',
        'charge_increased'    => 'Charge Increased (12 Months)',
        'shown_records'       => 'Shown Park Water Records',
        'home_ownership'      => 'Home Ownership',
        'home_age'            => 'Home Age',
        'residency_duration'  => 'Length of Residency',
        'charges_to_push_out' => 'Charges Used to Push Out?',
    ];
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-10">
        @foreach($stats as $key => $counts)
        @if($counts->count() > 0)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 text-sm mb-3">{{ $statLabels[$key] ?? $key }}</h3>
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
        @endif
        @endforeach
    </div>

    {{-- Cross-tab Analysis --}}
    <h2 class="text-base font-bold text-gray-500 uppercase tracking-wider mb-1">Cross-tab Analysis</h2>
    <p class="text-xs text-gray-400 italic mb-4">Percentages are row-relative. "I don't pay separately" responses are excluded from charge columns. With small sample sizes treat these as directional indicators only.</p>

    @php
    $crosstabs = [
        'Home Age vs. Monthly Water Charge' => $homeAgeCrosstab,
        'Length of Residency vs. Monthly Water Charge' => $residencyCrosstab,
        'Home Ownership vs. Monthly Water Charge' => $ownershipCrosstab,
    ];
    @endphp

    @foreach($crosstabs as $title => $crosstab)
    @if(count($crosstab) > 0)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-4 overflow-x-auto">
        <h3 class="font-semibold text-gray-700 text-sm mb-4">{{ $title }}</h3>
        <table class="text-xs w-full border-collapse">
            <thead>
                <tr>
                    <th class="text-left font-semibold text-gray-500 pr-4 pb-2 whitespace-nowrap"></th>
                    @foreach($chargeBucketOrder as $bucket)
                    <th class="text-center font-semibold text-gray-500 px-2 pb-2 whitespace-nowrap">{{ $bucket }}</th>
                    @endforeach
                    <th class="text-center font-semibold text-gray-500 px-2 pb-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($crosstab as $rowLabel => $buckets)
                @php $rowTotal = array_sum($buckets); @endphp
                @if($rowTotal > 0)
                <tr class="border-t border-gray-100">
                    <td class="text-gray-700 font-medium pr-4 py-2 whitespace-nowrap">{{ $rowLabel }}</td>
                    @foreach($chargeBucketOrder as $bucket)
                    @php
                        $cell = $buckets[$bucket] ?? 0;
                        $pct = $rowTotal > 0 ? round($cell / $rowTotal * 100) : 0;
                        $intensity = $pct >= 40 ? 'bg-blue-100 font-semibold text-blue-900' : ($pct >= 20 ? 'bg-blue-50 text-blue-800' : 'text-gray-600');
                    @endphp
                    <td class="text-center px-2 py-2 rounded {{ $intensity }}">
                        @if($cell > 0){{ $cell }}<br><span class="text-gray-400">{{ $pct }}%</span>@else<span class="text-gray-200">—</span>@endif
                    </td>
                    @endforeach
                    <td class="text-center px-2 py-2 text-gray-500 font-medium">{{ $rowTotal }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endforeach

    {{-- Per-person cost distribution --}}
    @if($perPersonCount > 0)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-10">
        <h3 class="font-semibold text-gray-700 text-sm mb-1">Per-Person Cost Distribution</h3>
        <p class="text-xs text-gray-400 mb-4">Estimated cost per household member (charge band midpoint ÷ household size). Based on {{ $perPersonCount }} responses with both charge range and household size.</p>
        <ul class="space-y-2">
            @foreach($perPersonBuckets as $label => $count)
            <li class="text-sm">
                <div class="flex justify-between mb-0.5">
                    <span class="text-gray-700">{{ $label }} / person</span>
                    <span class="text-gray-500 font-medium ml-2 shrink-0">{{ $count }}
                        <span class="text-gray-300">({{ $perPersonCount > 0 ? round($count/$perPersonCount*100) : 0 }}%)</span>
                    </span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-400 rounded-full"
                         style="width: {{ $perPersonCount > 0 ? round($count/$perPersonCount*100) : 0 }}%"></div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Response Table --}}
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-gray-500 uppercase tracking-wider">All Responses</h2>
        <button id="redact-toggle" onclick="toggleRedact()"
            class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition"
            data-redacted="true">
        </button>
    </div>
    <p id="redact-notice" class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-3">
        Free-text columns are hidden to protect respondent privacy. Toggle above to show them.
    </p>
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
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Pressure to Leave</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">Charges = Push Out?</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 redactable-col">Pressure Description</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 redactable-col">Comments</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 redactable-col">Contact Email</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($responses as $i => $r)
                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/50' }} hover:bg-blue-50/30">
                    <td class="px-4 py-3">
                        <button type="button" onclick="openResponse({{ $r->id }})"
                            class="text-blue-600 hover:underline font-medium">{{ $total - $i }}</button>
                    </td>
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
                    <td class="px-4 py-3 text-gray-800 max-w-xs">
                        @if($r->pressure_to_leave)
                            @php $ptl = array_filter($r->pressure_to_leave, fn($v) => $v !== 'No' && $v !== 'Prefer not to say'); @endphp
                            @if(count($ptl))
                                <span class="text-red-600 font-medium text-xs">{{ implode('; ', $ptl) }}</span>
                            @else
                                <span class="text-gray-500 text-xs">{{ implode('; ', $r->pressure_to_leave) }}</span>
                            @endif
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-800 whitespace-nowrap">
                        <span class="{{ $r->charges_to_push_out === 'Yes' ? 'text-red-600 font-medium' : '' }}">
                            {{ $r->charges_to_push_out ?? '—' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs redactable-col">
                        @if($r->pressure_description)
                            <span class="block truncate" title="{{ $r->pressure_description }}">{{ $r->pressure_description }}</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs redactable-col">
                        @if($r->additional_comments)
                            <span class="block truncate text-xs" title="{{ $r->additional_comments }}">{{ $r->additional_comments }}</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap text-sm redactable-col">
                        @if($r->contact_email)
                            {{ $r->contact_email }}
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <button type="button"
                            onclick="openResponse({{ $r->id }})"
                            class="text-xs px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-lg transition whitespace-nowrap">
                            View
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Response Detail Modal --}}
    <div id="response-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 text-lg">Response Detail</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <div id="modal-body" class="px-6 py-5 space-y-4 text-sm text-gray-700"></div>
        </div>
    </div>

    <script>
    const responses = {!! json_encode($responses->map(fn($r) => [
        'id'                   => $r->id,
        'submitted_at'         => $r->submitted_at->format('M j, Y'),
        'water_charge_range'   => $r->water_charge_range,
        'household_size'       => $r->household_size,
        'separate_charge'      => $r->separate_charge,
        'charge_calculation'   => $r->charge_calculation,
        'charge_increased'     => $r->charge_increased,
        'shown_records'        => $r->shown_records,
        'home_ownership'       => $r->home_ownership,
        'home_age'             => $r->home_age,
        'residency_duration'   => $r->residency_duration,
        'pressure_to_leave'    => $r->pressure_to_leave,
        'charges_to_push_out'  => $r->charges_to_push_out,
        'pressure_description' => $r->pressure_description,
        'additional_comments'  => $r->additional_comments,
        'contact_email'        => $r->contact_email,
    ])) !!};

    function openResponse(id) {
        const r = responses.find(x => x.id === id);
        if (!r) return;
        const fields = [
            ['Date', r.submitted_at],
            ['Water Charge', r.water_charge_range],
            ['Household Size', r.household_size],
            ['Separate Bill', r.separate_charge],
            ['Calculation', r.charge_calculation],
            ['Charge Increased', r.charge_increased],
            ['Shown Records', r.shown_records],
            ['Ownership', r.home_ownership],
            ['Home Age', r.home_age],
            ['Residency', r.residency_duration],
            ['Charges = Push Out?', r.charges_to_push_out],
        ];
        let html = '<dl class="grid grid-cols-2 gap-x-6 gap-y-3">';
        fields.forEach(([label, val]) => {
            html += `<dt class="font-semibold text-gray-500">${label}</dt><dd class="text-gray-800">${val || '—'}</dd>`;
        });
        html += '</dl>';
        if (r.pressure_to_leave && r.pressure_to_leave.length) {
            html += `<div class="mt-4 pt-4 border-t border-gray-100">
                <p class="font-semibold text-gray-500 mb-2">Pressure to Leave</p>
                <p class="text-gray-800">${escHtml(r.pressure_to_leave.join('; '))}</p>
            </div>`;
        }
        if (r.pressure_description) {
            html += `<div class="mt-4 pt-4 border-t border-gray-100">
                <p class="font-semibold text-gray-500 mb-2">Pressure Description</p>
                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">${escHtml(r.pressure_description)}</p>
            </div>`;
        }
        if (r.additional_comments) {
            html += `<div class="mt-4 pt-4 border-t border-gray-100">
                <p class="font-semibold text-gray-500 mb-2">Comments</p>
                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">${escHtml(r.additional_comments)}</p>
            </div>`;
        }
        if (r.contact_email) {
            html += `<div class="mt-4 pt-4 border-t border-gray-100">
                <p class="font-semibold text-gray-500 mb-1">Contact Email</p>
                <p class="text-gray-800">${escHtml(r.contact_email)}</p>
            </div>`;
        }
        document.getElementById('modal-body').innerHTML = html;
        document.getElementById('response-modal').classList.remove('hidden');
        document.getElementById('response-modal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('response-modal').classList.add('hidden');
        document.getElementById('response-modal').classList.remove('flex');
    }

    function escHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    </script>

    @endif

</div>

<script>
function toggleRedact() {
    const btn = document.getElementById('redact-toggle');
    const notice = document.getElementById('redact-notice');
    const isRedacted = btn.dataset.redacted === 'true';
    const cols = document.querySelectorAll('.redactable-col');
    cols.forEach(el => el.classList.toggle('hidden', isRedacted));
    btn.dataset.redacted = isRedacted ? 'false' : 'true';
    updateToggleButton();
    notice.classList.toggle('hidden', isRedacted);
}

function updateToggleButton() {
    const btn = document.getElementById('redact-toggle');
    const isRedacted = btn.dataset.redacted === 'true';
    if (isRedacted) {
        btn.textContent = 'Show free-text columns';
        btn.className = 'px-3 py-1.5 text-xs font-semibold rounded-lg border transition border-amber-400 text-amber-700 bg-amber-50 hover:bg-amber-100';
    } else {
        btn.textContent = 'Hide free-text columns';
        btn.className = 'px-3 py-1.5 text-xs font-semibold rounded-lg border transition border-gray-300 text-gray-600 bg-white hover:bg-gray-50';
    }
}

// Initialize: hide free-text columns by default
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.redactable-col').forEach(el => el.classList.add('hidden'));
    updateToggleButton();
});
</script>
@endsection
