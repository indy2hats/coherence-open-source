<div id="create_attributes" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add Attributes</h4>
            </div>
        
            <div class="modal-body">
                <table class="table table-striped files">
                    <thead>
                        <tr>
                            <th colspan="2">Name</th>
                            <th colspan="8">Values</th>
                            <th colspan="1"></th>
                        </tr>
                    </thead>
                    <tbody class="attribute-list">
                       
                        <tr  class='default'>
                            <td colspan="11" align="center">Nothing added yet</td>
                        </tr>
                       
                    </tbody>
                </table>
                <form action="{{route('attributes.store')}}" id="add_attributes_form" method="POST" autocomplete="off">
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
                              <input type="text" class="form-control  input-value" type="text" name="values" id="values" placeholder="Enter values comma seperated">
                              <div class="text-danger text-left field-error" id="label_values"></div>
                            </div>
                        </div>
                    </div>
                </div>
               </form>
                <div class="submit-section mt20 text-right">
                    <button class="btn btn-primary save-attribute">Save</button>
                </div>
                
            </div>

        </div>
    </div>
</div>

<!-- attributes Modal -->