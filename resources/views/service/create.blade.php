@extends ('orbit.master')

@section ('content')
    <!-- BEGIN PAGE HEADER-->
    <h1 class="page-title"> Create Service </h1>
    <!-- END PAGE HEADER-->

    @include ('service.form')

@endsection


@section('scripts')
    <script src="/js/init_select2.js?id={{ str_random(6) }}"></script>
@endsection

@section('inline-scripts')

    function loadServiceMatters()            {
        //$("#matters").val( {{ isset($matter_services) ? json_encode( $matter_services ) : '' }} );                
        $("#matters").select2().val({{ isset($matter_services) ? json_encode( $matter_services ) : '[]' }}).trigger("change");
    }       
         
@endsection