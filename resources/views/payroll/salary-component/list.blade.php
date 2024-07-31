 <table class="table table-hover salary-component-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Status</th>
            @can('manage-tasks')
            <th class="text-right">Action</th>
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach($components as $componentItem)        
        <tr>
            <td>{{$componentItem->title}}</td>
            <td>{{ucwords($componentItem->type)}}</td>
            <td>{{$componentItem->statusLabel}}</td>
            <td class="text-right">
                <a class="dropdown-item edit-component-button" href="#" data-id="{{$componentItem->id}}"
                    data-tooltip="tooltip" data-placement="top" title="Edit"> <i
                        class="ri-pencil-line m-r-5"></i></a>                |
                <a class="dropdown-item delete_component_onclick" href="#" data-toggle="modal"
                    data-target="#delete_component" data-id="{{$componentItem->id}}" data-tooltip="tooltip"
                    data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
            </td>           
        </tr>
        @endforeach
        @foreach($defaultComponents as $component)        
        <tr>
            <td>{{$component}}</td>
            <td>{{ ($component=='Incentives') ? 'Earning' : 'Default'}}</td>
            <td>Active</td> 
            <td class="text-right"></td>
        </tr>
        @endforeach
    </tbody>
</table>