@component('mail::message')
    # Thanks for purchasing {{ $course->title }}

    If this is a fist purchased on {{config('app.name')}}, then a new account has been created for you, and you
    Have fun with the new course.
    @component('mail::button', ['url' => url('login')])
        Login
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
