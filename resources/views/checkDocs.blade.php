@extends('layouts.app')

@section('content')

    <div class="container">
      <form method="POST" action="{{url('document')}}">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>First Document</label>
              <input class="form-control" type="file" name="doc1">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Second Document</label>
              <input class="form-control" type="file" name="doc1">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">
            <div class="form-group">
              <input type="submit" name="submit" value="Submit Document" class="btn btn-primary">
            </div>
          </div>   
        </div>
      </form>
    </div>

@endsection