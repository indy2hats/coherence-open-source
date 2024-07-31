<!-- Project filess Modal -->
<div class="modal custom-modal fade" id="show-project-credentials" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                    <h2 class="modal-title text-center"><strong>Project Credentials</strong></h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped files">
        <thead>
            <tr>
                <th>Type</th>
                <th>Value</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projectCredentials as $item)
            <tr>
                <td>{{$item->type}}</td>
                <td>{{$item->value}}</td>
                <td>{{$item->created_at_format}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
            </div>
        </div>
    </div>
</div>
<!-- /Project files Modal -->