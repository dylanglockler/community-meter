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
            'charges_to_push_out' => $responses->whereNotNull('charges_to_push_out')->countBy('charges_to_push_out')->sortDesc(),
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

        // ── Cross-tab helpers ─────────────────────────────────────────────
        $chargeBucketOrder = ['Under $50', '$50–$99', '$100–$149', '$150–$199', '$200–$299', '$300+'];
        $chargeBucketMap = array_merge(
            array_fill_keys(['Under $25', '$25–$49'], 'Under $50'),
            array_fill_keys(['$50–$74', '$75–$99'], '$50–$99'),
            ['$100–$149' => '$100–$149', '$150–$199' => '$150–$199'],
            array_fill_keys(['$200–$249', '$250–$299'], '$200–$299'),
            array_fill_keys(['$300–$349', '$350–$399', '$400 or more'], '$300+')
        );

        $buildCrosstab = function (array $rowOrder, string $rowField) use ($responses, $chargeBucketMap, $chargeBucketOrder) {
            $crosstab = [];
            foreach ($responses as $r) {
                $bucket = $chargeBucketMap[$r->water_charge_range] ?? null;
                if ($bucket === null) continue;
                $row = $r->$rowField ?? 'Unknown';
                if (!isset($crosstab[$row])) {
                    $crosstab[$row] = array_fill_keys($chargeBucketOrder, 0);
                }
                $crosstab[$row][$bucket]++;
            }
            $sorted = [];
            foreach ($rowOrder as $key) {
                if (isset($crosstab[$key])) $sorted[$key] = $crosstab[$key];
            }
            foreach ($crosstab as $key => $vals) {
                if (!array_key_exists($key, $sorted)) $sorted[$key] = $vals;
            }
            return $sorted;
        };

        $homeAgeCrosstab = $buildCrosstab(['Older', 'Newer', 'Not sure'], 'home_age');
        $residencyCrosstab = $buildCrosstab(['Less than 1 year', '1–3 years', '3–5 years', 'More than 5 years'], 'residency_duration');
        $ownershipCrosstab = $buildCrosstab(['I own my home', 'Park owns my home', 'Other', 'Not sure'], 'home_ownership');

        // ── Per-person cost distribution ──────────────────────────────────
        $chargeMidpoints = [
            'Under $25' => 12.50,  '$25–$49' => 37.00,   '$50–$74' => 62.00,
            '$75–$99'   => 87.00,  '$100–$149' => 124.50, '$150–$199' => 174.50,
            '$200–$249' => 224.50, '$250–$299' => 274.50, '$300–$349' => 324.50,
            '$350–$399' => 374.50, '$400 or more' => 425.00,
        ];
        $householdNums = ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5+' => 5];
        $perPersonBuckets = ['Under $25' => 0, '$25–$49' => 0, '$50–$74' => 0, '$75–$99' => 0, '$100–$149' => 0, '$150+' => 0];
        $perPersonCount = 0;
        foreach ($responses as $r) {
            $mid = $chargeMidpoints[$r->water_charge_range] ?? null;
            $hhNum = $householdNums[$r->household_size] ?? null;
            if ($mid === null || $hhNum === null) continue;
            $pp = $mid / $hhNum;
            $perPersonCount++;
            if ($pp < 25)        $perPersonBuckets['Under $25']++;
            elseif ($pp < 50)    $perPersonBuckets['$25–$49']++;
            elseif ($pp < 75)    $perPersonBuckets['$50–$74']++;
            elseif ($pp < 100)   $perPersonBuckets['$75–$99']++;
            elseif ($pp < 150)   $perPersonBuckets['$100–$149']++;
            else                 $perPersonBuckets['$150+']++;
        }

        // ── Displacement signals ──────────────────────────────────────────
        $pressureYesValues = [
            'Yes — I\'ve received a buyout or offer to purchase my home',
            'Yes — through fees, charges, or rule enforcement',
            'Yes — my lease was not renewed or I was threatened with eviction',
            'Yes — other',
        ];
        $displacement = [
            'total_answered'      => 0,
            'total_with_pressure' => 0,
            'by_home_age'         => [
                'Older'    => ['pressure' => 0, 'total' => 0],
                'Newer'    => ['pressure' => 0, 'total' => 0],
                'Not sure' => ['pressure' => 0, 'total' => 0],
            ],
            'by_type'              => array_fill_keys($pressureYesValues, 0),
            'charges_to_push'      => ['Yes' => 0, 'No' => 0, 'Not sure' => 0],
        ];
        foreach ($responses as $r) {
            if ($r->pressure_to_leave === null) continue;
            $displacement['total_answered']++;
            $hasAny = false;
            foreach ($r->pressure_to_leave as $val) {
                if (in_array($val, $pressureYesValues)) {
                    $hasAny = true;
                    $displacement['by_type'][$val]++;
                }
            }
            if ($hasAny) {
                $displacement['total_with_pressure']++;
            }
            $age = $r->home_age ?? 'Not sure';
            if (isset($displacement['by_home_age'][$age])) {
                $displacement['by_home_age'][$age]['total']++;
                if ($hasAny) $displacement['by_home_age'][$age]['pressure']++;
            }
            if ($r->charges_to_push_out && isset($displacement['charges_to_push'][$r->charges_to_push_out])) {
                $displacement['charges_to_push'][$r->charges_to_push_out]++;
            }
        }

        return view('admin.dashboard', compact(
            'responses', 'total', 'stats', 'insights',
            'chargeBucketOrder', 'homeAgeCrosstab', 'residencyCrosstab', 'ownershipCrosstab',
            'perPersonBuckets', 'perPersonCount', 'displacement'
        ));
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
            'home_age', 'residency_duration', 'pressure_to_leave', 'charges_to_push_out',
            'pressure_description', 'additional_comments',
        ];

        $callback = function () use ($responses, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            foreach ($responses as $r) {
                $row = array_map(function ($col) use ($r) {
                    $val = $r->$col;
                    return is_array($val) ? implode(' | ', $val) : $val;
                }, $columns);
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
