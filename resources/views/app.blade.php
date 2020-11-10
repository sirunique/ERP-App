<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
    <!-- Twitter meta-->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@pratikborsadiya">
    <meta property="twitter:creator" content="@pratikborsadiya">
    <!-- Open Graph Meta-->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Vali Admin">
    <meta property="og:title" content="Vali - Free Bootstrap 4 admin theme">
    <meta property="og:url" content="http://pratikborsadiya.in/blog/vali-admin">
    <meta property="og:image" content="http://pratikborsadiya.in/blog/vali-admin/hero-social.png">
    <meta property="og:description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
    <title>Vali Admin - Free Bootstrap 4 Admin Template</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vali') }}/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vali') }}/css/font-awesome.min.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body class="app sidebar-mini">
    <div id="app">
        <app></app>
    </div>

    <script src="{{mix('js/app.js')}}"></script>

    <!-- Essential javascripts for application to work-->
    {{-- <script src="{{ asset('vali') }}/js/jquery-3.3.1.min.js"></script> --}}
    <script src="{{ asset('vali') }}/js/popper.min.js"></script>
    <script src="{{ asset('vali') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('vali') }}/js/main.js"></script>
     <!-- The javascript plugin to display page loading on top-->
     <script src="{{ asset('vali') }}/js/plugins/pace.min.js"></script>
    {{-- Canvas JS For Chart --}}
    <script src="{{ asset('vali') }}/js/canvas.js"></script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="{{ asset('vali') }}/js/plugins/chart.js"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('vali') }}/js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('vali') }}/js/plugins/dataTables.bootstrap.min.js"></script>
    
    <script type="text/javascript" src="{{ asset('vali') }}/js/plugins/jquery.vmap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vali') }}/js/plugins/jquery.vmap.world.js"></script>
    <script type="text/javascript" src="{{ asset('vali') }}/js/plugins/jquery.vmap.sampledata.js"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
    
</body>
</html>