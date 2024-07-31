<div class="task-session">

    <div class="table-wrapper">
    
        <div class="table-responsive">
    
    
            <table class="table table-hover sessionTable">
    
                <thead>
    
                    <tr>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
    
                </thead>
    
                <tbody>
                    @forelse($asset->assetUser as $getUser)
                    <tr>
                        <td>{{$getUser->user->full_name ?? 'Deleted User' }}</td>
                        <td>{{ $getUser->assigned_date ? date_format(new DateTime($getUser->assigned_date), 'F d, Y') : '' }}
                            - {{ $getUser->status == 'allocated' ?  date_format(new DateTime(), 'F d, Y') : ($getUser->updated_at ? date_format(new DateTime($getUser->updated_at), 'F d, Y') : '') }}</td>
                        <td>@if($getUser->status == 'allocated')
                            <label class="label label-success">{{ ucwords(str_replace('_', ' ', $asset->status)) }}</label>
                            @endif
                            @if($getUser->status == 'inactive')
                            <label class="label label-info">{{ ucwords(str_replace('_', ' ', $getUser->status)) }}</label>
                            @endif
                            @if($getUser->status == 'ticket_raised')
                            <label class="label label-warning">{{ ucwords(str_replace('_', ' ', $getUser->status)) }}</label>
                            @endif</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" align="center">
                            No Data Found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
    
            </table>
        </div>
    
    
    
    </div>
    </div>
    
