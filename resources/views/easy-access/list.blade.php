 <table class="table table-hover">

                    <thead>

                        <tr>
                            <th>Name</th>
                            <th>Link</th>

                            <th class="text-right">Action</th>

                        </tr>

                    </thead>

                    <tbody>
                      
                        <?php $i=0 ?>
                        @foreach($list as $item)
                        <tr>
                            <td>{{$item['name']}}</td>
                            <td>{{$item['link']}}</td>
                    <td class="text-right">
                        <a class="dropdown-item edit-button" href="#" data-name="{{$item['name']}}" data-link="{{$item['link']}}" data-id="{{$i}}"
                            data-tooltip="tooltip" data-placement="top" title="Edit"> <i
                                class="ri-pencil-line m-r-5"></i></a>
                        |
                        <a class="dropdown-item delete_item_onclick" href="#" data-toggle="modal"
                            data-target="#delete_item" data-id="{{$i}}" data-tooltip="tooltip"
                            data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
                    </td>
                        </tr>
                        <?php $i++ ?>
                   @endforeach

                    </tbody>

                </table>