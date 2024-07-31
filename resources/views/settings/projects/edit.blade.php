@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox tabs-container">
            <div class="ibox-title">
                <h5>Edit Project Settings</h5>               
            </div>
            <div class="ibox-content">
                <form action="{{route('projects_settings.update')}}" id="edit_projects_settings" method="POST" autocomplete="off"  enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    @if(isset($settings['project_kanban_view']))
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{ $settings['project_kanban_view']['label']}}</label>
                        <div class="col-md-10">
                            <input type="checkbox" class="form-check-input" name="project_kanban_view" id="project_kanban_view" {{ $settings['project_kanban_view']['value'] == '1' ? 'checked' : '' }} style="display:block;">
                            <div class="text-danger text-left field-error" id="label_project_kanban_view"></div>
                        </div>                        
                    </div>
                    @endif
                    @if(isset($settings['project_show_task_actual_estimate']))
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{ $settings['project_show_task_actual_estimate']['label']}}</label>
                        <div class="col-md-10">
                            <div class="form-group form-focus select-focus focused">
                                <select class="chosen-select" id="project_show_task_actual_estimate" name="project_show_task_actual_estimate[]" multiple>
                                    @foreach ($roles as $role)
                                    <option value="{{$role->id}}" {{ (in_array($role->id, $projectChoosenData['projectShowTaskActualEstimate'])) ? 'selected': '' }}
                                        >{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                            
                    </div>
                    @endif
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <div class="col-md-4 col-md-offset-2">
                            <button class="btn btn-primary btn-sm" type="submit" id="submit">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/settings/projects/script.min.js') }}"></script>
@endsection