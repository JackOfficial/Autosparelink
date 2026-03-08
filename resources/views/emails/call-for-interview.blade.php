<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Call for interview</title>
</head>
<body>
   <h3>Hello {{ $last_name }}</h3>
   <p>We hope this email finds you well.</p>
   <p>Your Application on the position of {{ $title }} has been reviewed by our hiring team.</p>
   <p>Now, the next step is to come for the interview. we will tell you the time!</p>
   <p>We wish you the best luck!</p>
   <p>Happy Family Hiring Manager.</p>
   <footer>
    This email was sent to {{ $email }}. If it is not yours, kindly disregard it.
   </footer>
</body>
</html>