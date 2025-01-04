@foreach ($skills as $skill)
    <li class="list-group-item search_item recent_search {{$skill->title}}" >
        <span class="search_name">{{ $skill->skill }}</span> 
    </li>
@endforeach
