@foreach ($projectManagersData as $projectManager)

<h4>{{$projectManager->user->full_name}}
<small style="padding-left: 5px;">{{$projectManager->user->designation['name']}} </small></h4>

@endforeach
@can('manage-projects')
<div class="text-right">
    <button type="button" class="btn btn-info btn-circle btn-lg" data-toggle="modal" data-target="#assign_leader"><i class="ri-add-line"></i></button></div>
@endcan