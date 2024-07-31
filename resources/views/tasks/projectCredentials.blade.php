<!-- Project filess Modal -->
<div class="modal custom-modal fade" id="show-project-credentials" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Project Credentials</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped files">
                    <thead>
                        <tr>
                            <th width="20%;">Type</th>
                            <th>Value</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projectCredentials as $item)
                        <tr class="task-project-credentials">
                            <td>{{$item->type}}</td>
                            <td>{!! nl2br($item->value) !!}</td>
                            <td>@if($item->path != '') <a class="dropdown-item" href="{{ asset('storage/'.$item->path)}}" download><i class="ri-download-line"></i> Download</a> @endif</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" align="center">No Credentials Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /Project files Modal -->