@extends('layout.main')

@section('content')
<!-- page title -->
<div class="row">
	<div class="col-md-12">
		<strong>
			<h2 class="page-title">Archived Projects</h3>
		</strong>
	</div>
</div>
<!-- /page title -->
<div class="content-div animated fadeInUp">
	@include('projects.archived.search')
	<div class="main ibox-content">
		@include('projects.archived.list')
	</div>
</div>

@include('projects.delete')
@endsection

@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/resources/projects/script-min.js') }}"></script>
<script type="text/javascript">
	inputsloader();
    function inputsloader() {
        $('.chosen-select').chosen();
    }

    $(document).on('change','.change-archive-project',function(){
    var status = $(this).prop("checked");
     $.ajax({
            type: 'POST',
            url: '/change-archive-project',
            data: {
                'is_archived': $(this).prop("checked"),
                'id':$(this).data('id'),
            },
            success: function(response) {
                if (status) {
                    toastr.success('Project Archived!', 'Archived');
                    setTimeout(loadArchivedProjects, 1000);
                }
                else {
                    toastr.success('Project removed from Archived list!', 'Changed');
                    setTimeout(loadArchivedProjects, 1000);
                }
            },error: function(error) {
                toastr.error('Something Went Wrong!', 'Error');
            }
        });
});

    function loadArchivedProjects() {
        window.location = "/archived-projects/";
    }

     $(document).on('change', '#search_project_name', function(e) {
            var id = $(this).val();
            window.location = "/projects/"+id;
        });
        $(document).on('change', '#search_project_company', function(e) {
            searchList(e);
        });
        $('#search_project_priority').change(function(e) {
            searchList(e);
        });

        $(document).on('change', '#projectCategory', function(e) {
            searchList(e);
        });
            function searchList(e) {
        e.preventDefault();
        $('#search-project').submit();
    }
</script>
@endsection