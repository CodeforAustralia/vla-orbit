    <div class="note note-info padding-bottom-20" id="{{ $dashboard->id }}">
        @if( in_array( \App\Http\helpers::getRole(), ['Administrator']) )
        <a href="/dashboard/delete/{{ $dashboard->id }}" class="btn btn-xs red pull-right delete-content"> Delete </a>
        <a href="/dashboard/show/{{ $dashboard->id }}" class="btn btn-xs green pull-right"> Edit </a> 
        @endif
        <h4 class="block bold font-grey-mint note-title">{{ $dashboard->title }}</h4>
        <p class=""> {!! $dashboard->body !!} </p>
        @if ( \App\Http\helpers::getRole() == 'Administrator' )
        <p class="dashboard_date pull-right"> <small>{!! date('d/m/Y', strtotime($dashboard->created_at)) !!} </small> </p>
        @endif
    </div>