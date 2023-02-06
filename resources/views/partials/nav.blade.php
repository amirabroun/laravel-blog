@if (config('blog.can_users_register'))
@include('partials.nav.forMultiUser')

@else
@include('partials.nav.forSingleUser')
@endif