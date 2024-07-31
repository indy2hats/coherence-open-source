<form action="{{route('access-level-store')}}" method="post" id="access_levels_form">

    <table class="table table-striped" id="permissions_list">

        <thead>

            <tr>
                <th>Permissions</th>
                @foreach ($roles as $role)
                    <th>{{$role->display_name}}</th>
                @endforeach
            </tr>

        </thead>

        <tbody>
            @can('manage-roles')
                <tr>
                    <td></td>
                    @foreach ($roles as $role)
                        @if ($role->name != "administrator")
                            <td><button type="button" class="delete-role btn btn-default btn-sm" title="Delete role" data-id="{{$role->id}}" data-toggle="modal" data-target="#delete_role"><i class="ri-delete-bin-line"></i></button></td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endcan
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{$permission->display_name}}</td>
                    
                    @foreach ($roles as $role)
                        <td><input type="checkbox" name="{{$role->name}}-{{$permission->name}}" {{$role->hasPermissionTo($permission) ? 'checked' : '' }} id="{{strtolower(str_replace(" ","_", $role->name.'_'.$permission->name))}}" {{$role->name == "administrator" ? 'disabled' : ''}}><label class="col-form-labelform-check-label" for='{{strtolower(str_replace(" ","_", $role->name."_".$permission->name))}}'></label></td>
                    @endforeach

                </tr>
            @endforeach
            @can('manage-user-access-levels')
                <tr>
                    <td colspan="{{count($roles)+1}}">
                        <div class="submit-section">
                            <button class="btn btn-primary save-access-levels">Save</button>
                        </div>
                    </td>
                </tr>
            @endcan
        </tbody>

    </table>

</form>