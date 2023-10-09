<!DOCTYPE html>
<html lang="en">

<head>
  <script src='https://code.jquery.com/jquery-3.7.0.js'></script>
  <script src='https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js'></script>

<link href='https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css' rel='stylesheet'/>
<link href='https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css' rel='stylesheet'/>

  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard Control Manufacturing</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('./img/favicon/favicon.ico') }}" rel="icon">
  <link href="{{ asset('./img/favicon/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
<link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>

<body>
  @if (!Request::is(['login', 'register']))
    @include('partials.header')
    @include('partials.sidebar')
  @endif

    @yield('content')

  @if (!Request::is(['login', 'register']))
    @include('partials.footer')
  @endif

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>

    <script src="{{ asset('js/main.js') }}"></script>

    <script src='https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'></script>

</body>

</html>
