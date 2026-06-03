<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        if ($request->password !== env('ADMIN_PASSWORD')) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        session(['admin_authenticated' => true]);
        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $responses = SurveyResponse::orderByDesc('submitted_at')->get();
        $total = $responses->count();

        $stats = [
            'water_charge_range' => $responses->countBy('water_charge_range')->sortDesc(),
            'household_size'     => $responses->countBy('household_size')->sortDesc(),
            'separate_charge'    => $responses->countBy('separate_charge')->sortDesc(),
            'charge_calculation' => $responses->countBy('charge_calculation')->sortDesc(),
            'charge_increased'   => $responses->countBy('charge_increased')->sortDesc(),
            'shown_records'      => $responses->countBy('shown_records')->sortDesc(),
            'home_ownership'     => $responses->countBy('home_ownership')->sortDesc(),
            'home_age'           => $responses->countBy('home_age')->sortDesc(),
            'residency_duration' => $responses->countBy('residency_duration')->sortDesc(),
        ];

        $insights = [];
        if ($total > 0) {
            $highChargeRanges = ['$100–$149','$150–$199','$200–$249','$250–$299','$300–$349','$350–$399','$400 or more'];
            $insights = [
                'top_charge'       => $responses->countBy('water_charge_range')->sortDesc()->keys()->first() ?? '—',
                'high_charge_pct'  => round($responses->filter(fn($r) => in_array($r->water_charge_range, $highChargeRanges))->count() / $total * 100),
                'increased_pct'    => round($responses->filter(fn($r) => in_array($r->charge_increased, ['Yes, significantly', 'Yes, somewhat']))->count() / $total * 100),
                'denied_count'     => $responses->filter(fn($r) => $r->shown_records === 'Requested and denied')->count(),
                'unknown_calc_pct' => round($responses->filter(fn($r) => $r->charge_calculation === "I don't know")->count() / $total * 100),
                'separate_pct'     => round($responses->filter(fn($r) => $r->separate_charge === 'Yes, separate charge')->count() / $total * 100),
                'first_response'   => $responses->last()?->submitted_at,
                'latest_response'  => $responses->first()?->submitted_at,
            ];
        }

        return view('admin.dashboard', compact('responses', 'total', 'stats', 'insights'));
    }

    public function export()
    {
        $responses = SurveyResponse::orderByDesc('submitted_at')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="community-meter-responses.csv"',
        ];

        $columns = [
            'submitted_at', 'water_charge_range', 'household_size', 'separate_charge',
            'charge_calculation', 'charge_increased', 'shown_records', 'home_ownership',
            'home_age', 'residency_duration', 'additional_comments',
        ];

        $callback = function () use ($responses, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            foreach ($responses as $r) {
                fputcsv($handle, array_map(fn($col) => $r->$col, $columns));
            }
            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
