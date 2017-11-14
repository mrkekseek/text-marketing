@component('mail::message')

You credentials for Sign In:<br />

<b>Email</b>: {{ $credentials['email'] }}<br />
<b>Password</b>: {{ $credentials['pass'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
