@component('mail::message')

Hi {{ $name }}!<br />
A lead just texted you a reply. Please click <a href="{{ $link }}">{{ $link }}</a> to see it and reply if you like!

Thanks,<br>
{{ config('app.name') }}
@endcomponent