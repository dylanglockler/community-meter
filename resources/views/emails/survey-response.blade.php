<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; font-size: 15px; color: #222; background: #f5f5f5; margin: 0; padding: 24px; }
  .card { background: white; border-radius: 8px; max-width: 560px; margin: 0 auto; padding: 32px; border: 1px solid #e0e0e0; }
  h2 { font-size: 18px; margin: 0 0 4px; color: #111; }
  .meta { color: #888; font-size: 13px; margin-bottom: 24px; }
  table { width: 100%; border-collapse: collapse; }
  tr:nth-child(even) td { background: #f9f9f9; }
  td { padding: 10px 12px; vertical-align: top; border-bottom: 1px solid #eee; font-size: 14px; }
  td:first-child { color: #666; width: 55%; }
  td:last-child { font-weight: 600; color: #111; }
  .comments { background: #f9f9f9; border-radius: 6px; padding: 12px; font-size: 14px; color: #444; margin-top: 20px; }
  .footer { margin-top: 24px; font-size: 12px; color: #aaa; border-top: 1px solid #eee; padding-top: 16px; }
  .footer a { color: #4a90d9; }
  .anon { background: #e8f5e9; border-radius: 6px; padding: 10px 14px; font-size: 13px; color: #2e7d32; margin-bottom: 20px; }
</style>
</head>
<body>
<div class="card">
  <h2>New Survey Response</h2>
  <p class="meta">Submitted {{ $response->submitted_at->format('F j, Y \a\t g:i A') }}</p>

  <div class="anon">&#128274; Anonymous submission — no identifying information collected.</div>

  <table>
    <tr><td>Monthly water charge</td><td>{{ $response->water_charge_range }}</td></tr>
    <tr><td>Household size</td><td>{{ $response->household_size }}</td></tr>
    <tr><td>Separate water charge</td><td>{{ $response->separate_charge }}</td></tr>
    <tr><td>Charge calculation method</td><td>{{ $response->charge_calculation }}</td></tr>
    <tr><td>Charge increased in 12 months</td><td>{{ $response->charge_increased }}</td></tr>
    <tr><td>Shown park water records</td><td>{{ $response->shown_records }}</td></tr>
    <tr><td>Home ownership</td><td>{{ $response->home_ownership }}</td></tr>
    <tr><td>Home age</td><td>{{ $response->home_age }}</td></tr>
    <tr><td>Length of residency</td><td>{{ $response->residency_duration }}</td></tr>
  </table>

  @if($response->additional_comments)
  <div class="comments"><strong>Additional comments:</strong><br>{{ $response->additional_comments }}</div>
  @endif

  <div class="footer">
    <a href="{{ env('SURVEY_URL') ? env('SURVEY_URL') . '/admin' : url('/admin') }}">View all responses &rarr;</a>
  </div>
</div>
</body>
</html>
