<div class="wrapper wrapper-content grid">
    <div class="row">

        <div class="col-sm-8">

            <div class="ibox">

                <div class="no-margins tabs-container">
                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <div class="input-group">
                            <input type="text" placeholder="Search User" class="input form-control typeahead_name search-user" value="{{ $name ?? request()->name }}" autofocus>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn btn-primary search-button"> <i class="fa fa-search"></i> Search</button>
                            </span>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 form-group" >
                            <select class= "select form-control" id="user-role" name="user-role">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">
                                        {{$role->display_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 col-md-4 form-group" >
                            <select class= "select form-control" id="user-type" name="user-type">
                                <option value="">Select Type</option>
                                <option value="employee">Employee</option>
                                <option value="client">Client</option>
                            </select>
                        </div>
                    </div>

                    <div class="clients-list">

                        @include('users.list')

                    </div>

                </div>

            </div>

        </div>

        <div class="col-sm-4">

            <div class="panel viewDetails">
                @include('users.view')

            </div>

        </div>

    </div>
</div>
