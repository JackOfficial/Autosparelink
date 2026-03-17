<x-mail::message>
# New Message Received

You have a new inquiry from the contact form on **{{ config('app.name') }}**.

<x-mail::panel>
**From:** {{ $name }}  
**Email:** [{{ $email }}](mailto:{{ $email }})  
**Subject:** {{ $subjectText }}
</x-mail::panel>

### Message Content:
{{ $messageContent }}

<x-mail::button :url="config('app.url') . '/admin/mailbox/inbox'" color="primary">
View in Admin Dashboard
</x-mail::button>

*Note: You can reply directly to this email to contact the sender.*

Thanks,<br>
{{ config('app.name') }} Notification System
</x-mail::message>