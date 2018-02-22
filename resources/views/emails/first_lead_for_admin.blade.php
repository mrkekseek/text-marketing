@component('mail::message')
<p>{{ $user->firstname }} is Ready to Go Live</p>

<b>User email: </b>{{ $user->email }}<br />
<b>HA Account #: </b>{{ $ha->rep }}<br />
<b>Cell #: </b>{{ $user->phone }}<br />
@endcomponent
