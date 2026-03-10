@component('mail::message')

# Thank you for contacting JobAbroad

Hello **{{ $contact->name }}**,

We have received your message and our support team will review it shortly.

### Your Message
{{ $contact->message }}

Our team typically replies within **24 hours**.

Thanks,<br>
JobAbroad Support Team

@endcomponent