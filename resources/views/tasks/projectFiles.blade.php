<!-- Project filess Modal -->
<div class="modal custom-modal fade" id="show-project-files" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Project Documents</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped files">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Date Uploaded</th>
                            <th>Path / Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projectFiles as $file)
                        <tr>
                            <td>{{$file->name}}</td>
                            <td>{{$file->type}}</td>
                            <td>{{$file->created_at_format}}</td>
                            <td>@if($file->type == 'link')<a href="{{$file->path}}" target="blank"><i class="ri-eye-line"></i> Open</a>@else<a href="{{ asset('storage/'.$file->path)}}" download><i class="ri-download-line"></i> Download</a>@endif</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" align="center">No Documents Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /Project files Modal -->