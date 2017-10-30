<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <!-- CSRF Token -->
	    <meta name="csrf-token" content="{{ csrf_token() }}">
	    <link rel="stylesheet" href="{{asset('ext/vendor/bootstrap337/css/bootstrap.css')}}">
		<title>Home - Pendeteksi Dokumen Plagiat</title>
	</head>
	<body>
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="{{url('')}}">RabinKarp</a>
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
		        <li class="active"><a href="{{url('')}}">Home</a></li>
            <li><a href="{{url('login')}}">Login</a></li>
            <li><a href="{{url('user')}}">User</a></li>
            <li><a href="{{url('register')}}">Register</a></li>
		      </ul>
		      <ul class="nav navbar-nav navbar-right">
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dani <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="#">My History</a></li>
		            <li><a href="#">Log Out</a></li>
		          </ul>
		        </li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>

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

		<script type="text/javascript" src="{{asset('ext/js/jquery-3.2.1.js')}}"></script>
		<script type="text/javascript" src="{{asset('ext/vendor/bootstrap337/js/bootstrap.js')}}"></script>
		<script type="text/javascript">
			$(document).ready(function(e) {
				// alert('jquery berhasil jalan');
			})
		</script>
	</body>
</html>