<!-- Branches Modal -->
<div class="modal custom-modal fade" id="show-branches" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close branch-close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Branches</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped files">
                    <thead>
                        <tr>
                            <th colspan="2">Name</th>
                            <th colspan="8">URL</th>
                            <th colspan="1"></th>
                        </tr>
                    </thead>
                    <tbody class="branch-list">
                        @forelse($branches as $branch)
                        <tr>
                            <td colspan="2" >{{$branch->name}}</td>
                            <td colspan="8"><a href="{{$branch->url}}" target="blank"> {{ $branch->url }}</a></td>
                            <td colspan="1"><a class='dropdown-item delete-branch' href='#' data-id='{{$branch->id}}' title="Delete"><i class='ri-delete-bin-line m-r-5'></i></a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" align="center">No Branches Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <form id="add_branch_form" method="POST" autocomplete="off">
                @csrf
                <div class="append-new">
                    <div class="row" style="padding-top:10px;">
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input style="width: 100%;" type="text" class="form-control input-value" type="text" name="name" id="name" placeholder="Name">
                                <div class="text-danger text-left field-error" id="label_name"></div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group" style="width: 100%;">
                              <input type="text" class="form-control  input-value" type="text" name="url" id="url" placeholder="URL">
                              <div class="text-danger text-left field-error" id="label_url"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="task_id" id="task_id" value="{{$task->id}}">
                </form>
                <div class="submit-section mt20 text-right">
                    <button class="btn btn-primary save-branch">Save</button>
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- Branches Modal -->