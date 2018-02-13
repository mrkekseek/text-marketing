@component('mail::message')

Hi!<br />
Lead {{ $name }} just clicked on the link in your text and is a very hot lead. Try to reach them ASAP -  <a href="{{ $link }}">{{ $link }}</a>!

Thanks,<br>
{{ config('app.name') }}
@endcomponent