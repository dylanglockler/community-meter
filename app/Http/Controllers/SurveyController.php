<?php

namespace App\Http\Controllers;

use App\Mail\SurveyResponseReceived;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SurveyController extends Controller
{
    public function show()
    {
        return view('survey.show');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'water_charge_range' => 'required|string',
            'household_size'     => 'required|string',
            'separate_charge'    => 'required|string',
            'charge_calculation' => 'required|string',
            'charge_increased'   => 'required|string',
            'shown_records'      => 'required|string',
            'home_ownership'     => 'required|string',
            'home_age'           => 'required|string',
            'residency_duration' => 'required|string',
            'additional_comments'=> 'nullable|string|max:2000',
        ]);

        $validated['submitted_at'] = now();
        $response = SurveyResponse::create($validated);

        $recipients = array_filter([
            env('MAIL_TO_1'),
            env('MAIL_TO_2'),
        ]);

        foreach ($recipients as $address) {
            Mail::to($address)->send(new SurveyResponseReceived($response));
        }

        return redirect()->route('thank-you');
    }

    public function thankYou()
    {
        $count = SurveyResponse::count();
        return view('survey.thank-you', compact('count'));
    }
}
