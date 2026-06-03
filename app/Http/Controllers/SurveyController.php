<?php

namespace App\Http\Controllers;

use App\Mail\SurveyResponseReceived;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class SurveyController extends Controller
{
    public function show()
    {
        return view('survey.show');
    }

    public function store(Request $request)
    {
        // 3 submissions per IP per 24 hours — checked but never stored
        $key = 'survey:' . sha1($request->ip());
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $minutes = ceil(RateLimiter::availableIn($key) / 60);
            return back()->withErrors(['rate_limit' => "You've already submitted several responses. Please try again in {$minutes} minutes."]);
        }
        RateLimiter::hit($key, 86400);

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
            'contact_email'      => 'nullable|email|max:255',
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
