@guest
    <a href="{{ route('login') }}">Login</a>
@endguest
<form action="{{ route('logout') }}" method="post">
    @csrf
    <button type="submit">Log out</button>
</form>
@foreach($courses as $course)
    <h2>{{ $course->title }}</h2>
    <p>{{ $course->description }}</p>
@endforeach
