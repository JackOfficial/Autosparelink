<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reset Password</title>
</head>
<body>
  <form action="/reset-password" method="post">
 @csrf
  <div>
    <input type="hidden" value="{{ request()->route('token') }}" name="token">
  </div>
  <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
         @error('email')
            <div>{{ $message }}</div>
        @enderror
      </div>
      <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
         @error('password')
            <div>{{ $message }}</div>
        @enderror
      </div>
      <div>
        <label for="password_confirmation">Confirm Password</label>
        <input type="password_confirmation" name="password_confirmation" id="password_confirmation">
         @error('password_confirmation')
            <div>{{ $message }}</div>
        @enderror
      </div>
       <button type="submit">Reset Password</button>
</form>
     
</body>
</html>