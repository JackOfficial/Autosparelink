<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Application sent</title>
</head>
<body>
   <h3>Hello {{ $firstname }} {{ $lastname }}</h3>
   <p>Your Application on the position of {{ $position }} has been received successfully.</p>
   <p>HFRO hiring team is going to be reviewing your application. If you get selected, you will be informed.</p>
   <p>We wish you the best luck!</p>

   <footer>
    This email was sent to {{ $email }}. If it is not yours, kindly disregard it.
   </footer>
</body>
</html>