<div class="col-md-12">
    <div class="ibox-content">
        <div class="table-responsive">
            <table class="table table-striped credentialsTable">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Content</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userCredentials as $value)
                    <tr>
                        <td>{{$value->type}}</td>
                        <td><span>{{$value->username}}</span>
                            @if($value->username)
                            <button style="margin-left:5px" class="copyText"><i class="fa fa-clipboard"></i></button>
                            @endif
                        </td>
                        <td><input class="single-input-field" style="outline: none;" type="password" value='{{$value->decrypt_password}}' readonly>
                            <button style="margin-left:5px" class="copyPass"><i class="fa fa-clipboard"></i></button>
                        </td>
                        <td>{!! $value->value !!}</td>
                        <td>
                            @can('manage-project-credentials')
                            <a class="share-data" data-id='{{$value->id}}' data-tooltip="tooltip" data-placement="top" title="Share"><i class="fa fa-share-alt"></i></a><a style="margin-left:10px;" class="edit-data" data-id='{{$value->id}}' data-tooltip="tooltip" data-placement="top" title="Edit"><i class="ri-pencil-line"></i></a><a style="margin-left:10px;" class="delete-credential-data" data-toggle="modal" data-target="#delete_credential" data-id='{{$value->id}}' data-tooltip="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line"></i></a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>
</div>