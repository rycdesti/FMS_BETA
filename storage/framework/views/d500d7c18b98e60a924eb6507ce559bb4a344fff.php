<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#36A2CD">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.png')); ?>">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo e(asset(mix('css/app.css'))); ?>">
</head>

<body>
    <div id="app">
        <noscript>
            <div class="app flex-row align-items-center">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6 p-3">
                            <h1 class="text-center text-md-left">
                                <i class="fa fa-warning fa-2x"></i>
                            </h1>
                            <h4 class="pt-3">Browser not Support</h4>
                            <p class="text-muted">
                                Try other browser which have Javascript Support. Recommendation is
                                <a href="https://www.google.com/chrome/?brand=CHBD&gclid=CjwKCAjw6-_eBRBXEiwA-5zHaa78eJ_HfOScWr1D4PzHFZY2k2zRHc9pcHZ9gvAaHbljCsSC1gv0jBoCKIUQAvD_BwE&gclsrc=aw.ds">
                                    Google Chrome
                                </a>
                                or
                                <a href="https://www.mozilla.org/en-US/">
                                    Mozilla Firefox
                                </a>
                                <br>
                                <a class="btn btn-primary mt-3" href="<?php echo e(url()->current()); ?>">
                                    Reload Page
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </noscript>
    </div>
    <script defer src="<?php echo e(asset(mix('js/manifest.js'))); ?>"></script>
    <script defer src="<?php echo e(asset(mix('js/vendor.js'))); ?>"></script>
    <script defer src="<?php echo e(asset(mix('js/app.js'))); ?>"></script>
</body>

</html>
