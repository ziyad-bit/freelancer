@if ($transactions->count() > 0)
    @foreach ($transactions as $transaction)
        <tr data-created_at="{{ $transaction->created_at }}" >
            <th scope="row">{{ $transaction->type }}</th>
            <td>${{ $transaction->amount }}</td>
            <td>{{ $transaction->project_title }}</td>
            <td>{{ $transaction->owner_name }}</td>

            @if ($transaction->receiver_name)
                <td>{{ $transaction->receiver_name }}</td>
            @else
                <td>-</td>
            @endif

            <td>{{ $transaction->created_at }}</td>
            <td>
                <form action="{{route('transaction.milestone.release')}}" method="POST">
                    @csrf

                    <input type="hidden" name="amount" value="{{$transaction->amount}}">
                    <input type="hidden" name="project_id" value="{{$transaction->project_id}}">
                    <input type="hidden" name="receiver_id" value="{{$transaction->receiver_id}}">

                    <button type="submit" class="btn btn-success">release</button>
                </form>
                
            </td>
        </tr>
    @endforeach
@else
    @if (!request()->ajax())
        <h3>no transactions</h3>
    @endif
@endif
