 <tr>
                                    <td class="client-avatar"><img alt="image" src="@if($user->image_path){{ asset('storage/'.$user->image_path) }}@else{{ asset('img/user.jpg') }}@endif"> </td>
                                    <td><a onclick="view('{{ $user->id }}')">{{ $user->full_name }}</a></td>
                                    <td>{{ $user->role->display_name }}</td>
                                    <td class="mail-cell"><i class="ri-mail-fill"> </i> {{ $user->email }}</td>
                                    <td><a class="edit-user" data-id="{{$user->id}}" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="ri-pencil-line"></i></a></td>
                                    <td><a class="delete-user" data-toggle="modal" data-target="#delete_employee" data-id="{{$user->id}}" data-tooltip="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line"></i></a></td>
                                </tr>