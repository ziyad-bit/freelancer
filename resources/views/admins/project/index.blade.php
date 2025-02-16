@extends('adminlte::page')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">
            {{ Session::get('success') }}
        </div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">
            {{ Session::get('error') }}
        </div>
    @endif

    <a class="btn btn-primary" href="{{ route('admin.project.create')}}" style="margin-top: 20px">
        add
    </a>

    <table class="table table-striped  table-hover m-1" >
        <thead class="thead-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">name</th>
                <th scope="col">created at</th>
                <th scope="col">control</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projects as $project)
                <tr>
                    <th scope="row">{{ $project->id }}</th>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.project.show',$project->id) }}" class='btn btn-success'>
                            show
                        </a>

                        <a href="{{ route('admin.project.edit',$project->id) }}" class='btn btn-primary'>
                            edit
                        </a>

                        <form action="{{ route('admin.project.destroy', $project->id) }}" method="POST" class="m-1 d-inline">
                            @csrf
                            @method('delete')

                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
    {{$projects->links()}}
@endsection

