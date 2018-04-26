@component('mail::message')
<p>{{ $user->firstname }} - Apologies for the delay (HomeAdvisor sometimes moves slowly). But don’t worry - your 30 day free trial starts only now.</p>
<p><b>1.</b> Your account is now live and sending out texts, with the templates we recommend.</p>
<p><b>2.</b> We will soon send a test text to your cell.</p>
<p><b>3.</b> You can log into your account anytime here - <a href="{{ config('app.url') }}">app.contractortexter.com</a></p>
<p><b>4.</b> Want 3 months free? Send us the name and contact info for a friend who would benefit from us - if they sign up, you save big!</p>
<p>Thanks,</p>
ContractorTexter Team
@endcomponent