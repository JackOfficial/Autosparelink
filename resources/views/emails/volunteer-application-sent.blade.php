<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Application sent</title>
</head>
<body>
   <h3>Hello {{ $name }},</h3>
   <p>Your Application for becoming our volunteer has been recieved.</p>
   <p>We will reach out to you.</p>
   <p>Thank you!</p>

   <footer>
    This email was sent to {{ $email }}. If it is not yours, kindly disregard it.
   </footer>
</body>
</html>