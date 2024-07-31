<div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Values</th>
                    @can('manage-assets')<th>Action</th>@endcan
                </tr>
            </thead>
            <tbody class="attribute-full-list">
                @if(count($attributes) > 0)
                @foreach ($attributes as $attribute)
                @php
                    $attributeValue = '';
                    $count = count($attribute->attribute_values);
                    foreach ($attribute->attribute_values as $index => $attribute_value) {
                        $attributeValue .= $attribute_value->value;
                        if ($index < $count - 1) {
                            // Add a comma and space if it's not the last value
                            $attributeValue .= ', ';
                        }
                    }
                @endphp
                <tr>
                    <td id="attribute-name-{{$attribute->id}}"> {{ $attribute->name }} </td>
                    <td id="attribute-values-{{$attribute->id}}"> {{ $attributeValue }} </td>
                    @can('manage-assets')
                    <td>
                        <a href="#" class="edit-attribute" data-target="#edit_attribute" data-id="{{ $attribute->id }}"
                            data-tooltip="tooltip" title="Edit" data-toggle="modal"> <i
                                class="ri-pencil-line m-r-5"></i></a>
                        <a class="dropdown-item delete-attribute" href="#" data-toggle="modal"
                            data-target="#delete_attribute" data-id="{{ $attribute->id }}" data-tooltip="tooltip"
                            data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
                    </td>
                    @endcan
                </tr>
                @endforeach
                @else
                <tr>
                     <td colspan="7" align="center" class="default">No Data Found</td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="pagination-div">
            {{$attributes->links()}}
        </div>

</div>