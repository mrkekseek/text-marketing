@component('mail::message')

Hi {{ $user->firstname }} - Welcome aboard!<br />

<b>1.</b> We are sending your info to HomeAdvisor to get connected. It takes them several days but your free trial won’t start until you’re connected.<br />
<b>2.</b> To go back to your dashboard to complete signup, please click <a href="https://app.contractortexter.com/">app.contractortexter.com</a><br />
<b>3.</b> Want 3 months free? Send us the name and contact info for a friend who would benefit from us - if they sign up, you save big!<br />

<p>Thanks,</p>

ContractorTexter Team
@endcomponent