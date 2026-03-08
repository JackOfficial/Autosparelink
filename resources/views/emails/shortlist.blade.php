<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>You have been shortlisted</title>
</head>
<body>
   <h3>Hello {{ $last_name }}</h3>
   <h3>Conglaturation!</h3>
   <p>Your have been shortlisted for the position of {{ $title }}.</p>
   <p>Now, the next step is to come for the exam. we will tell you the time!</p>
   <p>We wish you the best luck!</p>
   <p>Happy Family Hiring Manager.</p>
   <footer>
    This email was sent to {{ $email }}. If it is not yours, kindly disregard it.
   </footer>
</body>
</html>