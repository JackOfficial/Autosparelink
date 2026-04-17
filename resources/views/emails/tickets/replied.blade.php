<!DOCTYPE html>
<html>
<head>
    <style>
        .button {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 25px;
            display: inline-block;
        }
    </style>
</head>
<body style="font-family: sans-serif; color: #333;">
    <h2>Hello {{ $reply->ticket->user->name }},</h2>
    <p>A support agent has replied to your ticket regarding <strong>{{ $reply->ticket->subject }}</strong>.</p>
    
    <div style="background: #f4f4f4; padding: 20px; border-left: 4px solid #007bff; margin: 20px 0;">
        <p style="font-style: italic;">"{{ $reply->message }}"</p>
    </div>

    <p>You can view the full conversation and reply by clicking the button below:</p>
    
    <a href="{{ url('/user/tickets/'.$reply->ticket_id) }}" class="button">View Ticket Details</a>

    <p style="margin-top: 30px; font-size: 12px; color: #777;">
        Best Regards,<br>
        Support Team | autosparelink.com
    </p>
</body>
</html>