<div class="col-md-12">
        
        <div class="ibox-content">
            <div class="table-responsive">

                <table class="table table-striped credentialsTable">
        
                    <thead>

                        <tr>
                            <th>Title</th>

                            <th>Content</th>

                            <th>Action</th>

                        </tr>

                    </thead>
                   
                    <tbody>
                    @foreach($credentials as $credential)
                        <tr>
                            <td>{{$credential->title }}</td>

                            <td>{!! nl2br($credential->content) !!}</td>

                            <td>
                                <a class="edit-data" data-id="{{$credential->id}}" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="ri-pencil-line"></i></a>
                                <a style="margin-left:10px;" class="delete-credential-data" data-toggle="modal" data-target="#delete_credential" data-id="{{$credential->id}}" data-tooltip="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>