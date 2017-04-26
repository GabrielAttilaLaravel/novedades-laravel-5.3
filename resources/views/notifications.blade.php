@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <ul class="list-group">
                    @foreach($notifications as $notification)
                        <li class="list-group-item">
                            <a href="{{ url("notifications/{$notification->id}") }}" @if($notification->read_at == null) style="font-weight: bold" @endif>
                                {{--
                                    class_basename:
                                        Obtenemos el nombre de la clase sin su namespace
                                    strtolower:
                                        convertimos el nombre de la clase a minusculas
                                --}}
                                {{ trans('notifications.'.strtolower(class_basename($notification->type)), $notification->data) }}
                            </a>
                            <br> {{ $notification->created_at->format('d/m/Y h:ia') }}
                        </li>
                    @endforeach
                </ul>
                <p><a href="notifications/read-all">Mark all as read</a></p>
            </div>
        </div>
    </div>
@endsection