<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.head')
  
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

    <div class="wrapper">
        <div id="loader"></div>
        @include('layout.header')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container-full">
                @yield('content')
            </div>
        </div>
        <!-- /.content-wrapper -->
        @include('layout.footer')
        <div class="control-sidebar-bg"></div>
    </div>
</body>

</html>
