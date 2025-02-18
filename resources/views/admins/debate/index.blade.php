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

    <a class="btn btn-primary" href="{{ route('admin.debate.create')}}" style="margin-top: 20px">
        add
    </a>

    <table class="table table-striped  table-hover m-1" >
        <thead class="thead-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">initiator name</th>
                <th scope="col">opponent name</th>
                <th scope="col">status</th>
                <th scope="col">created at</th>
                <th scope="col">control</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($debates as $debate)
                <tr>
                    <th scope="row">{{ $debate->id }}</th>
                    <td>{{ $debate->initiator_name }}</td>
                    <td>{{ $debate->opponent_name }}</td>
                    <td>{{ $debate->status }}</td>
                    <td>{{ $debate->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.debate.show',$debate->id) }}" class='btn btn-success'>
                            show
                        </a>

                        <form action="{{ route('admin.debate.destroy', $debate->id) }}" method="POST" class="m-1 d-inline">
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
    {{$debates->links()}}
@endsection

