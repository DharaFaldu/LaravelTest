<div class="col-md-3" id="sidebar-wrapper">
    <div class="list-group list-group-flush">
        <a href="{{url('/home')}}" id="dashboard" class="list-group-item list-group-item-action bg-light">Dashboard</a>
        <a href="{{url('/users/')}}/{{auth()->user()->id}}" id="my-profile" class="list-group-item list-group-item-action bg-light">My Profile</a>
        @auth
        @if(auth()->user()->role_id != 3)
            <a href="{{url('/users')}}" id="users" class="list-group-item list-group-item-action bg-light">Users</a>
        @endif
        @endauth
    </div>
</div>

