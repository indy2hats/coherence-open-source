<table class="table table-striped" id="holiday_list">

    <thead>

        <tr>
            <th>Date</th>

            <th>Holiday</th>
            @can('manage-holidays')

            <th>Action</th>
            @endcan
        </tr>

    </thead>

    <tbody>
        @foreach($lists as $list)
        <tr>

            <td><span style="display: none;">{{$list->holiday_date}}</span>{{ date('d/m/Y',strtotime($list->holiday_date)) }}</td>

            <td>{{$list->holiday_name}}</td>
            @can('manage-holidays')
            <td><a class="edit-holiday" data-id="{{$list->id}}" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="ri-pencil-line"></i></a> |
                <a class="delete-holiday" data-toggle="modal" data-target="#delete_holiday" data-id="{{$list->id}}" data-tooltip="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line"></i></a></td>
            @endcan

        </tr>
        @endforeach

    </tbody>

</table>