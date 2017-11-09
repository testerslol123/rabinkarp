@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <label>Document 1 : </label>
      <div>
        @foreach($doc1 as $data) 
          {{$data}}
        @endforeach
      </div>
    </div>
    <div class="col-md-6">
      <label>Document 2 : </label>
      <div>
        @foreach($doc2 as $data) 
          {{$data}}
        @endforeach

      </div>
    </div>
  </div>
  
  <hr>
</div>
@endsection
