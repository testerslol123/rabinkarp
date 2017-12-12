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
  <div class="row">
    <div class="text-center">
      <h4>Hasil Hash dengan Algoritma Rabin Karp</h4>
    </div>
    <div class="col-md-6">
      @foreach($rkDoc1 as $rk)
        <span>{ {{$rk}} } </span>
      @endforeach
    </div>
    <div class="col-md-6">
      @foreach($rkDoc2 as $rk2)
        <span>{ {{$rk2}} }</span>
      @endforeach
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="text-center">
      <h3>Kesamaan Hash yang didapati</h3>
    </div>
    <div class="text-center">
      @foreach($rkDoc1 as $rk1)
        @foreach($rkDoc2 as $rk2)
          @if($rk1 == $rk2) 
            <span>{ {{$rk1}} }</span>
          @endif
        @endforeach
      @endforeach
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="text-center">
      <h4>Hasil Sorensen Index Similarity</h4>
    </div>
    <div class="text-center">
      <h3>{{$similarity * 100}} %</h3>
    </div>
  </div>
</div>
@endsection
