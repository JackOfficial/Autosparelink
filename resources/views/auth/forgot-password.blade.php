<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recover Password</title>
</head>
<body>
    <form method="post" action="/forgot-password">
        @csrf
      <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
         @error('email')
            <div>{{ $message }}</div>
        @enderror
      </div>
      <button type="submit">Submit</button>
    </form>
</body>
</html>