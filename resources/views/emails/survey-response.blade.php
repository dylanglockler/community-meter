A new anonymous survey response was submitted on {{ $response->submitted_at->format('F j, Y \a\t g:i A') }}.

--- Survey Answers ---

1. Monthly water charge: {{ $response->water_charge_range }}
2. Household size: {{ $response->household_size }}
3. Separate water charge: {{ $response->separate_charge }}
4. Charge calculation method: {{ $response->charge_calculation }}
5. Charge increased in 12 months: {{ $response->charge_increased }}
6. Shown park water records: {{ $response->shown_records }}
7. Home ownership: {{ $response->home_ownership }}
8. Home age: {{ $response->home_age }}
9. Length of residency: {{ $response->residency_duration }}
10. Additional comments: {{ $response->additional_comments ?: '(none)' }}

---
This response is anonymous. No identifying information was collected.
View all responses at {{ env('SURVEY_URL') ? env('SURVEY_URL') . '/admin' : url('/admin') }}
