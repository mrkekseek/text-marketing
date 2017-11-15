@component('mail::message')

You have a message from {{ config('app.name') }} website

<b>Name</b>: {{ $sender['name'] }}<br />
<b>Email</b>: {{ $sender['email'] }}<br />
<b>Message</b>: {{ $sender['message'] }}


Thanks,<br>
{{ config('app.name') }}
@endcomponent
