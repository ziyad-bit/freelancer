@foreach ($searches as $search)
    <li class="list-group-item search_item recent_search" >
        <span class="search_name">{{ $search->search }}</span> 
    </li>
@endforeach
