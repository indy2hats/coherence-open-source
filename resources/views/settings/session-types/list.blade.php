 <table class="table table-hover session-type-table">

                    <thead>

                        <tr>
                            <th>Session Type</th>
                           
                             @can('manage-tasks')

                            <th class="text-right">Action</th>
                             @endcan

                        </tr>

                    </thead>

                    <tbody>
                      
                        @foreach($types as $type)
                        
                        <tr>
                            <td>{{$type->title}}</td>
                            
                            @can('manage-tasks')
                    <td class="text-right">
                        <a class="dropdown-item edit-button" href="#" data-id="{{$type->id}}"
                            data-tooltip="tooltip" data-placement="top" title="Edit"> <i
                                class="ri-pencil-line m-r-5"></i></a>
                        |
                        <a class="dropdown-item delete_type_onclick" href="#" data-toggle="modal"
                            data-target="#delete_type" data-id="{{$type->id}}" data-tooltip="tooltip"
                            data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
                    </td>
                    @endcan
                        </tr>
                        @endforeach

                    </tbody>

                </table>