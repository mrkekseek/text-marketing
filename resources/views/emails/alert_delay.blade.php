@component('mail::message')

@if (count($alerts) == 1)
Hi,
<p>You received a {{ $alerts[0]->answers[0]->value }} Star{{ $alerts[0]->answers[0]->value > 1 ? 's' : '' }} response. Please click here to see it <a href="{{ config('app.url').'/surveys/analysis' }}">{{ config('app.name') }}</a></p>
@else
Hi,
<p>
@foreach ($alerts as $alert)
{{ $alert->answers[0]->value }} Star{{ $alert->answers[0]->value > 1 ? 's' : '' }} response. Please click here to see it <a href="{{ config('app.url').'/surveys/analysis' }}">{{ config('app.name') }}</a><br />
@endforeach
</p>
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent