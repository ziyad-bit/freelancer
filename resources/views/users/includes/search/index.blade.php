@foreach ($projects as $project)
    <li class="list-group-item search_item recent_search {{$project->title}}" >
        <span class="search_name">{{ $project->title }}</span> 
    </li>
@endforeach
