<div class="modal custom-modal fade" id="add_documents" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add Documents</h4>
            </div>
            <div class="modal-body">
                 <form action="{{route('uploadTaskFiles')}}" class="dropzone" id="dropzoneForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" id="upload_task_id" value="">
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                            </form>
                                
            </div>
        </div>
    </div>
</div>