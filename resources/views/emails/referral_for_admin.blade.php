@component('mail::message')
<p>{{ $user->firstname }} has refer a friend.</p>

<b>User email: </b>{{ $user->email }}<br />
<b>User phone: </b>{{ $user->phone }}<br />
<b>Referral name: </b>{{ $referral['name'] }}<br />
<b>Referral contacts: </b>{{ $referral['contacts'] }}<br />
@endcomponent