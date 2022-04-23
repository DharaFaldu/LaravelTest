@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        @include('layouts.sidebar')

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">{{ __('Users') }} <button class="btn btn-primary float-right" id="addUser" >Add</button></div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="container">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th width="200px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($userList) && $userList->count())
                                @foreach($userList as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role->name }}</td>
                                        <td>
                                            <form action="{{ route('users.destroy',$user->id) }}" method="Post">
                                                <a class="btn btn-success editUser" data-id="{{$user->id}}">Edit</a>
                                                @csrf
                                                @method('DELETE')
                                                @auth
                                                @if(auth()->user()->role_id == 1)
                                                    <button type="submit" class="btn btn-danger delete">Delete</button>
                                                @endif
                                                @endauth
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10">There are no data.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        {!! $userList->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEditUserModel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading" >Edit User</h4>
            </div>
            <div class="modal-body">
                <form id="addEditUserForm" >
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" id="user_id">

                    <div class="form-group input-group">
                        <label for="name" class="col-sm-4 control-label">Name <span class="required">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" required="">
                        </div>
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8">
                            <div id="err_name" class="required"></div>
                        </div>
                    </div>

                    <div class="form-group input-group">
                        <label for="email" class="col-sm-4 control-label">Email <span class="required">*</span></label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="" readonly="">
                        </div>
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8">
                            <div id="err_email" class="required"></div>
                        </div>
                    </div>

                    <div class="form-group input-group">
                        <label for="role" class="col-sm-4 control-label">Role <span class="required">*</span></label>
                        <div class="col-sm-8">
                            <select name="role_id" id="role_id">
                                @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group input-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-primary" id="saveBtn" value="create">Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".list-group-item").removeClass('active');
    $("#users").addClass('active');

    $(document).ready(function() {
        $('.delete').click(function(e) {
            if(!confirm('Are you sure you want to delete this user?')) {
                e.preventDefault();
            }
        });
    });

    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#email,#name').on('keyup', function () {
            if($(this).val() != '') {
                $('#err_'+$(this).attr('id')).html(' ');
            }
        });

        $('#addUser').click(function () {
            $('#modelHeading').html("Add User");
            $('#saveBtn').val("add-user");
            $('#addEditUserForm').trigger("reset");
            $('#user_id').val('');
            $("#email").attr("readonly", false);
            $("#email").attr("required", "required");
            $('#addEditUserModel').modal('show');
        });

        $('body').on('click', '.editUser', function () {
            var user_id = $(this).data('id');
            $.get("{{ route('users.index') }}" +'/' + user_id +'/edit', function (data) {
                $('#modelHeading').html("Edit User");
                $('#saveBtn').val("edit-user");
                $("#email").attr("readonly", true);
                $("#email").removeAttr("required");
                $('#addEditUserModel').modal('show');

                $('#user_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('select[name^="role_id"] option[value="' +data.role_id+ '"]').attr("selected","selected");
            })
        });

        $('#saveBtn').click(function (e) {
            $.ajax({
                data: $('#addEditUserForm').serialize(),
                url: "{{ route('users.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#addEditUserForm').trigger("reset");
                    $('#addEditUserModel').modal('hide');
                    window.location.href = "{{url('users')}}";
                },
                error: function (xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    var error = err.errors;
                    $('#err_email').html(error.email);
                    $('#err_name').html(error.name);
                    $('#saveBtn').html('Save');
                }
            });
        });
    });
</script>
@endsection
