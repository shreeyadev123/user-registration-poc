<h2>Login</h2>
@if ($errors->any())
    <p style="color:red">{{ $errors->first() }}</p>
@endif
<form method="POST" action="/login">
    @csrf
    <input name="email" type="email" placeholder="Email" required><br>
    <input name="password" type="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
<a href="/register">Don't have an account?</a>
