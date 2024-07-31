
    <div class="ibox-content">
        <div class="table-responsive">
            <table class="table table-striped listTable">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Title</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $item)
                    
                        <tr>
                        <td>@foreach($item['type'] as $type)
                            <span class="label label-primary">{{$type}}</span>
                        @endforeach</td>
                        <td><a href="/guidelines/{{$item['id']}}">{{$item['title']}}</a></td>
                        <td class="text-right">
                                <span class="dlt-i">
                                    <a href="/guidelines/{{$item['id']}}" data-tooltip="tooltip" data-placement="top" title="View Content">
                                        <i class="ri-eye-line" aria-hidden="true"></i>
                                    </a>
                                </span>

                            <span class="edit-i">
                                <a data-tooltip="tooltip" data-placement="top" title="Edit">
                                    <i data-id="{{$item['id']}}" class="ri-pencil-line edit-guideline" aria-hidden="true"></i>
                                </a>
                            </span>

                            <span class="dlt-i">
                                <a href="#" class="delete-guideline" data-id="{{$item['id']}}" data-tooltip="tooltip" data-placement="top"title="Delete">
                                    <i data-toggle="modal" data-target="#delete_guideline" class="ri-delete-bin-line" aria-hidden="true"></i>
                                </a>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>