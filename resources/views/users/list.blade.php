@if(count($users)>0)
    <div>

        <ul class="nav nav-tabs pt-10">

            <li class="active"><a data-toggle="tab" href="#active-users"> Active</a></li>

            <li class=""><a data-toggle="tab" href="#inactive-users">In-Active</a></li>

        </ul>

        <div class="tab-content">

            <div id="active-users" class="tab-pane active user-client-table-wrap">
                <div class="table-responsive list">
                    <table class="table table-hover listTable">
                        <tbody>
                            @foreach($users as $user)
                            @if($user->status == 1)
                            @include('users.display-users')
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="inactive-users" class="tab-pane user-client-table-wrap">
                <div class="table-responsive list">
                    <table class="table table-hover listTable">
                        <tbody>
                            @foreach($users as $user)
                            @if($user->status == 0)
                            @include('users.display-users')
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
    </div>

@else
    <div class="alert alert-danger">User Not Found.</div>
@endif