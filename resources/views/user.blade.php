@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        @foreach($users as $user)
         @if ($user->id != Auth::user()->id)
         <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <img src="{{url('images/user/user.png')}}" width="50" alt="">
                        </div>
                        <div class="col-7">
                            <h5 class="card-title">{{ $user->name }}</h5>
                            <p class="card-text">{{ $user->email }}</p>
                        </div>
                        <div class="col-3">
                            <a class="btn btn-primary mt-2" href="{{ route('chat-to-friend', $user->id) }}" >Chat</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
         @endif
        @endforeach
        </div>
    </div>
</div>
@endsection
