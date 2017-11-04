@component('mail::message')

Activate your account

@component('mail::button', ['url' => $url])
Activate
@endcomponent

Thanks,<br />
{{ config('app.name') }}
@endcomponent