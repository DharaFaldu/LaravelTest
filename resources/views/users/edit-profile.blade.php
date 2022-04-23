@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        @include('layouts.sidebar')

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Update Profile </div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="container">

                        <form action="{{ route('users.update',auth()->user()->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group input-group">
                                <label for="name" class="col-sm-4 control-label">Name <span class="required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{$user->name}}" required="">
                                </div>
                                @error('name')
                                <div class="col-sm-4"></div>
                                <div class="col-sm-8">
                                    <div class="required">{{ $message }}</div>
                                </div>
                                @enderror
                            </div>

                            <div class="form-group input-group">
                                <label for="email" class="col-sm-4 control-label">Email <span class="required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{$user->email}}" required>
                                </div>
                                @error('email')
                                <div class="col-sm-4"></div>
                                <div class="col-sm-8">
                                    <div class="required">{{ $message }}</div>
                                </div>
                                @enderror
                            </div>

                            <div class="form-group input-group">
                                <label for="role" class="col-sm-4 control-label">Role <span class="required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="role_id" name="role_id" value="{{$user->role->name}}" readonly>
                                </div>
                            </div>

                            <div class="form-group input-group">
                                <label for="password" class="col-sm-4 control-label">Password </label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password">
                                </div>
                                @error('password')
                                <div class="col-sm-4"></div>
                                <div class="col-sm-8">
                                    <div class="required">{{ $message }}</div>
                                </div>
                                @enderror
                            </div>

                            <div class="form-group input-group">
                                <label for="password-confirm" class="col-sm-4 control-label">Confirm Password </label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" autocomplete="new-password" value="">
                                </div>
                            </div>

                            <div class="form-group input-group">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-8">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".list-group-item").removeClass('active');
    $("#my-profile").addClass('active');
</script>
@endsection
