<table class="table table-striped listTable">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($types as $type)
                                    <tr>
                                        <td>{{$type->type}}</td>
                                        <td>{{$type->amount}}</td>
                                        <td>{!!$type->description!!}</td>
                                        <td>
                        <a class="dropdown-item edit-overhead-type" href="#" data-id="{{ $type->id }}" data-tooltip="tooltip" data-placement="top" title="Edit"> <i class="ri-pencil-line m-r-5"></i></a>
                         |
                        <a class="dropdown-item delete-overhead-type" href="#" data-toggle="modal" data-target="#delete_overhead_type" data-id="{{ $type->id }}" data-tooltip="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>

                    </td>
                                    </tr>
                                    @endforeach
                                   
                                </tbody>
                            </table>