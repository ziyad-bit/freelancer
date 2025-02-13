@extends('adminlte::page')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    <a class="btn btn-primary" href="{{ route('admin.user.create') }}" style="margin-top: 20px">
        add
    </a>

        <table class="table table-striped  table-hover m-1" >
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">slug</th>
                    <th scope="col">Control</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->slug }}</td>
                        <td>
                            <div class="d-flex flex-column flex-sm-row">
                                <a href="{{ route('admin.user.show', $user->slug) }}" class="btn btn-success m-1">
                                    show
                                </a>

                                <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary m-1">
                                    Edit
                                </a>

                                <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="m-1">
                                    @csrf
                                    @method('delete')

                                    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
@endsection
