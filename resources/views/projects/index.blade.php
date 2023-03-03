@extends('projects.master')
@section('content')
    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-3-4">

            <h2 id="getstarted">Build new project</h2>
            <p>
                Consider below instructions and build your projects!
            </p>
          {{--  <div class="uk-overflow-container">
                <table class="uk-table uk-text-nowrap">
                    <thead>
                    <tr>
                        <th>Step</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Pending</td>
                        <td>Name your project.</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Pending</td>
                        <td>Select origin point on the image (0,0).</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Pending</td>
                        <td>Select initial point of the device on the image (#,#).</td>
                    </tr>
                    </tbody>
                </table>
            </div>--}}

            <h3>Project details</h3>
            <form class="uk-form uk-form-horizontal" method="POST" action="{{ route('project.store') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="uk-alert uk-alert-danger">{{ $error }}</div>
                    @endforeach
                @endif
                <div class="uk-form-row">
                    <label class="uk-form-label" for="form-h-it">Project Name</label>
                    <div class="uk-form-controls">
                        <input name="project_name" value="{{ old('project_name') }}" type="text" id="form-h-it" placeholder="Project name">
                    </div>
                </div>
                <div class="uk-form-row">
                    <label class="uk-form-label" for="form-h-it">First Device ID</label>
                    <div class="uk-form-controls">
                        <input name="device_id" value="{{ old('device_id') }}" type="text" id="form-h-it" placeholder="Device ID">
                    </div>
                </div>
                <div class="uk-form-row">
                    <label class="uk-form-label" for="form-h-it">Movement Data (txt or csv)</label>
                    <div class="uk-form-controls">
                        <div class="uk-form-file">
                            <button class="uk-button">Upload file</button>
                            <input value="{{ old('movement_file') }}" name="movement_file" type="file" onchange="document.getElementById('movement-file-name').innerHTML = this.files[0].name;">
                            <div id="movement-file-name"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <label class="uk-form-label" for="form-h-it">Plan Image</label>
                    <div class="uk-form-controls">
                        <div class="uk-form-file">
                            <button class="uk-button">Upload image</button>
                            <input value="{{ old('plan_image') }}" name="plan_image" type="file" onchange="document.getElementById('plan-image-name').innerHTML = this.files[0].name;">
                            <div id="plan-image-name"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <button class="uk-button uk-button-success  uk-width-1-2" type="submit">Submit</button>
                </div>
            </form>
        </div>
        @include('projects.right-bar')
    </div>
@endsection
