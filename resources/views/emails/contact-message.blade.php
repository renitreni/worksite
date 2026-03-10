@component('mail::message')

# New Contact Message

You received a new message from **JobAbroad Contact Form**

**Role:** {{ $contact->role }}

**Name:** {{ $contact->name }}

**Email:** {{ $contact->email }}

**Phone:** {{ $contact->phone }}

**Message**

{{ $contact->message }}

Thanks,<br>
{{ config('app.name') }}

@endcomponent