<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cognitive Head Hunter</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">

    <!-- stylesheet -->
    <link rel="stylesheet" href="/css/watson-bootstrap-dark.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/style.css">

    <!-- scripts -->
    <script type="text/javascript" src="/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/js/d3.v2.min.js"></script>

    <script type="text/javascript" src="/js/demo.js"></script>
    <script type="text/javascript" src="/js/analyze.js"></script>
    <script type="text/javascript" src="/js/validator.min.js"></script>
</head>
<body>
    <a id="jazzhub" href="https://github.com/alanbraz/cognitive-head-hunter" target="_blank" class="visible-lg visible-md">
        <img src="/images/forkme.png" alt="Fork me on JazzHub" style="position: absolute; top: 0; right: 0; border: 0;z-index:16;">
    </a>
    <div class="container">
        <!-- Content -->
        @yield('content')

        <!-- footer -->
        @include('footer')
    </div>
</body>
</html>