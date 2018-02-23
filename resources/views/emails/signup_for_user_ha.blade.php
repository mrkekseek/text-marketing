@component('mail::message')

Hi {{ $user->firstname }},<br />
<br />
Thanks for signing up with us!<br />
Right now we are sending your account info over to HomeAdvisor to get you connected. Normally it takes a day or so for them - once they do we will put in our template text and send you a test text for your approval. We won't make you live until then.<br />
<p>We will be in touch soon, thanks!</p>
<p>Log into you account anytime here: <a href="https://app.contractortexter.com">app.contractortexter.com</a></p>
<p>To see a short video explanation, <a href="https://contractortexter.com/video">CLICK HERE</a></p>
Uri<br />
CEO | Cofounder <br />
{{ config('mail.from.address') }}
@endcomponent