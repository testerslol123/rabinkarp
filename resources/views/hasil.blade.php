@extends('layouts.app')

@section('content')
<div class="container">
  <!-- PER KALIMAT -->
  <div class="row">
    <div class="col-md-6">
      <label>Document 1 : </label>
      <div>
        @php
          $param = count($setorKalimat)-1;
          $i = 0;
          $kesamaanKalimat = array();
        @endphp
        @foreach($setorKalimat as $kalimat)
          @php
            array_push($kesamaanKalimat, $kalimat);
          @endphp
          @if($i < ($param-1))
            @php
              $i++;
            @endphp
          @else 
            @break;
          @endif
        @endforeach

        @php 
          $i = 0;
          $panjangKalimat = count($kesamaanKalimat);
          $checkIfExist = 0;
        @endphp
        @foreach($doc1 as $data) 
          @php
            $checkIfExist = 0;
          @endphp
          @foreach($kesamaanKalimat as $samaKalimat)
            @if($data == $samaKalimat)
              <span style="color:red">{{$samaKalimat}}</span>
              @php
                $checkIfExist = 1;
              @endphp
            @endif
          @endforeach
          @if($checkIfExist == 0) 
            <span>{{$data}}</span>
          @endif
        @endforeach
      </div>
    </div>
    <div class="col-md-6">
      <label>Document 2 : </label>
      <div>

        @php 
          $i = 0;
          $panjangKalimat = count($kesamaanKalimat);
          $checkIfExist = 0;
        @endphp
        @foreach($doc2 as $data) 
          @php
            $checkIfExist = 0;
          @endphp
          @foreach($kesamaanKalimat as $samaKalimat)
            @if($data == $samaKalimat)
              <span style="color:red">{{$samaKalimat}}</span>
              @php
                $checkIfExist = 1;
              @endphp
            @endif
          @endforeach
          @if($checkIfExist == 0) 
            <span>{{$data}}</span>
          @endif
        @endforeach
      </div>
    </div>
  </div>
  <hr>

  <!-- Per KATA -->
  <div class="row">
    <div class="col-md-6">
      <label>Document 1 : </label>
      <div>
        @foreach($potongKata1 as $kata)
          @foreach($kata as $word)
            @if($word !== '')
              { {{$word}} }
            @endif 
          @endforeach
        @endforeach
      </div>
    </div>
    <div class="col-md-6">
      <label>Document 2 : </label>
      <div>
        @foreach($potongKata2 as $kata)
          @foreach($kata as $word)
            @if($word !== '')
              { {{$word}} }
            @endif 
          @endforeach
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
            @break
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
