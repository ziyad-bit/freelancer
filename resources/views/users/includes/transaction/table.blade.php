@if ($transactions->count() > 0)
    @foreach ($transactions as $transaction)
        <tr data-created_at="{{ $transaction->date }}">

            <th scope="row">{{ $transaction->type }}</th>
            <td>${{ $transaction->amount }}</td>
            <td>{{ $transaction->project_title }}</td>
            <td>{{ $transaction->owner_name }}</td>
            
            <td>{{ $transaction->receiver_name }}</td>
            

            <td>{{ $transaction->date }}</td>

            @if ($transaction->type === 'milestone' )
                <td>
                    <form action="{{ route('transaction.milestone.release') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $transaction->id }}">
                        <input type="hidden" name="amount" value="{{ $transaction->amount }}">
                        <input type="hidden" name="project_id" value="{{ $transaction->project_id }}">
                        <input type="hidden" name="receiver_id" value="{{ $transaction->receiver_id }}">
                        <input type="hidden" name="trans_id" value="true">

                        <button type="submit" class="btn btn-success">release</button>
                    </form>

                </td>
            @endif

        </tr>
    @endforeach
@else
    @if (!request()->ajax())
        <h3>no transactions</h3>
    @endif
@endif
