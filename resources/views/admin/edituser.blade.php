@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="container">
    <form action="{{url('edituser')}}" method="POST">
        {{csrf_field()}}
        <input type="hidden" name="user_id" value="{{$userData->id}}"/>
        <div class="form-group">
            <label>Nama : </label>
            <input type="text" class="form-control" name="nama" value="{{$userData->name}}" />
        </div>
        <div class="form-group">
            <label>Email : </label>
            <input type="email" class="form-control" name="email" value="{{$userData->email}}"/>
        </div>
        <button class="btn btn-primary">SUBMIT</button>
    </form>
</div>
@endsection

@section('scripts')
@endsection