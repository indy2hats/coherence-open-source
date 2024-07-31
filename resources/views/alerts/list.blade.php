 <table class="table table-hover">

     <thead>

         <tr>
             <th>Alert Type</th>
             <th>Date</th>
             <th>Title</th>
             <th>Content</th>
             <th>File Type</th>
             <th class="text-right">Action</th>

         </tr>

     </thead>

     <tbody>

         @foreach($list as $item)
         <tr>
             <td>{{$item->type}}</td>
             <td>{{$item->date_format}}</td>
             <td>{{$item->title}}</td>
             <td>{!!$item->image!!}</td>
             <td>{{$item->file_type}}</td>
             <td class="text-right">
                 <a class="dropdown-item delete-alert" href="#" data-toggle="modal" data-target="#delete_alert" data-id="{{$item->id}}" data-tooltip="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
             </td>
         </tr>
         @endforeach

     </tbody>

 </table>