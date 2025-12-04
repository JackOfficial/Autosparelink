<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
</head>
<body>
   <div>This is home page</div>
   @if(Auth::check())
    <div class="profile">
        <img src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/?d=mp&s=200' }}" alt="Avatar" width="50" height="50">
        <p>Name: {{ Auth::user()->name }}</p>
        <p>Email: {{ Auth::user()->email }}</p>
        <p>Role: {{ Auth::user()->getRoleNames()->first() }}</p>
    </div>
@endif
    @if(Auth::check())
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endif
</body>
</html>