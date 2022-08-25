@extends('layouts.app')

@section('content')
<div class="container">
    <chat :chats='{{$chats}}'  :friend='{{$friend}}'  :friends='{{$friends}}' :me='{{$me}}'></chat>
</div>
@endsection
