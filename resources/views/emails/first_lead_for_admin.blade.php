@component('mail::message')
<p>{{ $user->firstname }} is Ready to Go Live</p>

<b>User email: </b>{{ $user->email }}<br />
<b>HomeAdvisor Rep: </b>{{ $ha->rep }}<br />
@endcomponent
