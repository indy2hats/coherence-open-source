 <table class="table table-hover santa-table">

                    <thead>

                        <tr>
                            <th>Name</th>

                            <th>Phone</th>

                            <th>Address</th>
                            <th>Image</th>

                            <th class="text-right">Action</th>


                        </tr>

                    </thead>

                    <tbody>

                        @foreach($santas as $santa)

                        <tr>
                            <td>{{$santa->user->full_name ?? ''}}</td>
                            <td>{{$santa->phone ?? ''}}</td>
                            <td>{{$santa->address ?? ''}}</td>
                            <td>@if($santa->image != '') <img style="width:50px;" src="{{ asset('storage/'.$santa->image)}}" /> @else @endif</td>


                    <td class="text-right">
                        <a class="dropdown-item edit-button" href="#" data-id="{{$santa->id}}"
                            data-tooltip="tooltip" data-placement="top" title="Edit"> <i
                                class="ri-pencil-line m-r-5"></i></a>
                        |
                        <a class="dropdown-item delete_type_onclick" href="#" data-toggle="modal"
                            data-target="#delete_type" data-id="{{$santa->id}}" data-tooltip="tooltip"
                            data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
                    </td>

                        </tr>
                        @endforeach

                    </tbody>

                </table>
