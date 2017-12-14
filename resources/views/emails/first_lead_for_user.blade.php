@component('mail::message')
<p>Hi {{ $user['firstname'] }}</p>
<p>Your account is now connected and ready to go live. Please now go into the account, customize the text you want your leads to receive, and make the account live.</p>
<p>Please <a href="{{ config('app.url') }}">CLICK HERE</a> to enter your account.</p>
<p>If you have any questions, please email us back.</p>
<p>Thanks!</p>
@endcomponent