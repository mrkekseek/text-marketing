@component('mail::message')

Hi,
<p>You received a new response. Please click here to see it <a href="{{ config('app.url').'/surveys/analysis' }}">{{ config('app.name') }}</a></p>


Thanks,<br>
{{ config('app.name') }}
@endcomponent