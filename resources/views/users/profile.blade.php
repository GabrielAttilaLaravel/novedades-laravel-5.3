@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Profile</div>
                    <div class="panel-body">
                        <form class="form-horizontal"
                              role="form"
                              method="post"
                              action="{{ url('profile') }}"
                              enctype="multipart/form-data">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('description') ? ' has-error': '' }}">
                                <label for="description" class="col-md-4 control-label">Description</label>

                                <div class="col-md-6">

                                    <textarea name="description" id="description" class="form-control">{{ auth()->user()->profile->description }}</textarea>

                                    @if($errors->has('description'))
                                        <span class="help-block">
                                            <storn>{{ $errors->first('description') }}</storn>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($profile->avatar)
                                <p>
                                    <img src="{{ url('profile/avatar') }}" alt="">
                                </p>
                            @endif


                            <div class="form-group{{ $errors->has('avatar') ? ' has-error': '' }}">
                                <label for="avatar" class="col-md-4 control-label">Avatar</label>

                                <div class="col-md-6">
                                    <input type="file" id="avatar" name="avatar">

                                    @if($errors->has('avatar'))
                                        <span class="help-block">
                                            <storn>{{ $errors->first('avatar') }}</storn>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Update profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection