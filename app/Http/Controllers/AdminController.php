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

        return view('admin.dashboard', compact('responses', 'total', 'stats'));
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
