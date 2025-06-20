<h2>Welcome, {{ Auth::user()->name }}</h2>
<p>Your email: {{ Auth::user()->email }}</p>

<form method="POST" action="/logout">
    @csrf
    <button type="submit">Logout</button>
</form>
