<div class="col-md-12">
    <div class="ibox-content">
        <div class="table-responsive">
            <table class="table table-striped usercredentialsTable">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Type</th>
                        <th>Username</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userCredentials as $value)
                    <tr>
                        <td>{{$value->project->project_name}}</td>
                        <td>{{$value->type}}</td>
                        <td><span>{{$value->username}}</span>
                            @if($value->username)
                            <button style="margin-left:5px" class="copyText"><i class="fa fa-clipboard"></i></button>
                            @endif
                        </td>
                        <td><input class="single-input-field" style="outline: none;" type="password" value='{{$value->decrypt_password}}' readonly>
                            <button style="margin-left:5px" class="copyPass"><i class="fa fa-clipboard"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>