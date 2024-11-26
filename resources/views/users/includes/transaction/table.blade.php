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
        </tr>
    @endforeach
@else
    @if (!request()->ajax())
        <p>no transactions</p>
    @endif
@endif
