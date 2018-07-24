@component('mail::message')

{{ $user->firstname }} -<br />

Thanks for signing up!<br />

You can start sending out Pre-Appointment Confirmation texts right away. If you have any questions, please schedule a demo
here - <a href="https://calendly.com/contractortexter">calendly.com/contractortexter</a>.<br />

<p>Log back in to your account here: <a href="{{ config('app.url') }}">app.contractortexter.com</a></p>

<p>Thanks,</p>

ContractorTexter Team
@endcomponent