<head>
    <meta charset="UTF-8">
    <title> Zoho Admin - @yield('htmlheader_title', 'Your title here') </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/fullcalendar.print.min.css') }}" rel="stylesheet" type="text/css" media="print" />
    <link href="{{ asset('/css/all.css') }}" rel="stylesheet" type="text/css" />  
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.10.1/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- <script src="{{ asset('/js/env.js') }}" type="text/javascript"></script> -->

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};

        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.10.1/sweetalert2.min.js" type='text/javascript'></script>
</head>