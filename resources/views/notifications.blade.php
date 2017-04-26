@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <ul class="list-group">
                    @foreach($notifications as $notification)
                        <li class="list-group-item" @if($notification->is_new) style="font-weight: bold" @endif>
                            <a href="{{ $notification->redirect_url }}" >
                                {{--
                                    class_basename:
                                        Obtenemos el nombre de la clase sin su namespace
                                    strtolower:
                                        convertimos el nombre de la clase a minusculas
                                --}}
                                {{ $notification->description }}
                            </a>
                            <br> {{ $notification->date }}
                        </li>
                    @endforeach
                </ul>
                <p><a href="notifications/read-all">Mark all as read</a></p>
            </div>
        </div>
    </div>
@endsection