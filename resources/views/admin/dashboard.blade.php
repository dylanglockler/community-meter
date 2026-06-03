@extends('layouts.app')

@section('title', 'Admin Dashboard — Community Meter')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
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

    {{-- Summary Stats --}}
    <h2 class="text-lg font-bold text-gray-800 mb-4">Summary Statistics</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-10">

        @php
        $statLabels = [
            'water_charge_range' => 'Monthly Water Charge',
            'household_size'     => 'Household Size',
            'separate_charge'    => 'Separate Water Charge',
            'charge_calculation' => 'Charge Calculation Method',
            'charge_increased'   => 'Charge Increased in 12 Months',
            'shown_records'      => 'Shown Park Records',
            'home_ownership'     => 'Home Ownership',
            'home_age'           => 'Home Age',
            'residency_duration' => 'Length of Residency',
        ];
        @endphp

        @foreach($stats as $key => $counts)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 text-sm mb-3">{{ $statLabels[$key] }}</h3>
            <ul class="space-y-1.5">
                @foreach($counts as $label => $count)
                <li class="flex items-center gap-2 text-sm">
                    <div class="flex-1">
                        <div class="flex justify-between mb-0.5">
                            <span class="text-gray-700 truncate max-w-[160px]" title="{{ $label }}">{{ $label }}</span>
                            <span class="text-gray-500 font-medium ml-2">{{ $count }}</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full"
                                style="width: {{ $total > 0 ? round(($count / $total) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>

    {{-- Response Table --}}
    <h2 class="text-lg font-bold text-gray-800 mb-4">All Responses</h2>
    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
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
                <tr class="hover:bg-gray-50 {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/50' }}">
                    <td class="px-4 py-3 text-gray-400">{{ $total - $i }}</td>
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $r->submitted_at->format('M j, Y') }}</td>
                    <td class="px-4 py-3 text-gray-800 whitespace-nowrap">{{ $r->water_charge_range }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $r->household_size }}</td>
                    <td class="px-4 py-3 text-gray-800 whitespace-nowrap">{{ $r->separate_charge }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $r->charge_calculation }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $r->charge_increased }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $r->shown_records }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $r->home_ownership }}</td>
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

    @else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        No responses yet.
    </div>
    @endif

</div>
@endsection
