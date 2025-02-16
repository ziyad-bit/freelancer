@extends('adminlte::page')

@section('content')
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        <a class="btn btn-primary m-1" href="{{ route('admin.edit', $admin->id) }}" 
            role="button">
            edit 
        </a>

        {{-- admin data --}}
        <div class="card mb-3 card_profile" style="margin-top: 15px">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img src="{{ asset('/storage/images/users/' . $admin->image) }}" style="width:356px"
                        class="card-img image-profile" alt="..." />
                </div>

                <div class="col-md-8">
                    <ul class="list-group ">
                        <li class="list-group-item active">
                            <h4>information</h4>
                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile name_text">name</span>:
                            <span id="name" class="admin_name">{{ $admin->name }}</span>
                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile name_text">id</span>:
                            <span id="name" class="admin_name">{{ $admin->id }}</span>
                        </li>

                        <li class="list-group-item items_list ">
                            <span class="email">email</span>:
                            <span id="email" class="admin_email">{{ $admin->email }}</span>
                        </li>

                        <li class="list-group-item items_list ">
                            <span class="email">created at</span>:
                            <span id="email" class="admin_email">{{ $admin->created_at }}</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    @endsection
