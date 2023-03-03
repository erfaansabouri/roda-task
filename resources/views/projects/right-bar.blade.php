<div class="uk-width-medium-1-4">

    <div class="uk-panel uk-panel-box" data-uk-sticky="{top:35}">
        <ul class="uk-nav uk-nav-side" data-uk-scrollspy-nav="{closest:'li', smoothscroll:true}">
            <li class="uk-nav-header">Your projects</li>
            @foreach(Auth::user()->projects()->get() as $project)
                <li class="@if(Route::is('project.show', $project->uuid)) uk-active @endif"><a href="{{ route('project.show', $project->uuid) }}">{{ $project->name }}</a></li>
            @endforeach
        </ul>
    </div>

</div>
