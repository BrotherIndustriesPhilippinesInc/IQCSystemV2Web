<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="/iqcv2/resources/logo/iqc_logo.png">
        <!-- JQUERY -->
        <script defer src="/iqcv2/node_modules/jquery/dist/jquery.min.js"></script>

        <!-- LESS CSS -->
        <link rel="stylesheet/less" type="text/css" href="/iqcv2/css/style.less" />
        <script defer src="/iqcv2/node_modules/less/dist/less.min.js"></script>

        <!-- SWEETALERT2 -->
        <script defer src="/iqcv2/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

        <!-- MAINSCRIPT -->
        <script defer type="module" defer src="/iqcv2/js/main-script.js"></script>

        <!-- UPPY -->
        <link href="https://releases.transloadit.com/uppy/v4.13.2/uppy.min.css" rel="stylesheet"/>

        <!-- FLATPICKR -->
        <link rel="stylesheet" type="text/css" href="/iqcv2/non_module_libraries/flatpickr/dark.css">
        <script defer src="/iqcv2/non_module_libraries/flatpickr/flatpickr.js"></script>

        <!-- CHART JS -->
        <script defer src="/iqcv2/non_module_libraries/chartjs/chart.umd.min.js"></script>
        <script defer src="/iqcv2/non_module_libraries/chartjs/chartjs-adapter-date-fns.bundle.min.js"></script>

        <!-- DATATABLES -->
        <script defer src="/iqcv2/non_module_libraries/datatables/datatables.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/iqcv2/non_module_libraries/datatables/datatables.min.css">

        <!-- BOOSTRAP -->
        <link rel="stylesheet" href="/iqcv2/node_modules/bootstrap/dist/css/bootstrap.css">
        <script defer src="/iqcv2/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

        <!-- POPPER -->
        <script defer src="/iqcv2/non_module_libraries/popper/popper.min.js"></script>

        <!-- FONT AWESOME -->
        <script src="/iqcv2/non_module_libraries/fontawesome/fontawesome.js" crossorigin="anonymous"></script>

    </head>
    <body>
        <?php
        //Initialize Components
        require_once __DIR__ . '/views/components/buttons.php';
        require_once __DIR__ . '/views/components/textboxes.php';
        require_once __DIR__ . '/views/components/selects.php';
        require_once __DIR__ . '/views/components/dropdowns.php';
        require_once __DIR__ . '/helpers/MSQLServer.php';
        
        $button = new Buttons();
        $textbox = new Textboxes();
        $select = new Selects();
        $dropdown = new Dropdowns();

        // Router
        // Define routes and their corresponding callback functions
        $routes = [
            '/iqcv2/' => function() {
                include __DIR__ . '/views/pages/home.php';
            },
            '/iqcv2/choose_lots/inspection_result' => function() {
                include __DIR__ . '/views/pages/choose_lots/inspection_result.php';
            }
        ];

        // Get the current path
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Check if the path exists in the routes
        if (array_key_exists($path, $routes)) {
            // Call the associated function
            
            $routes[$path]();
        } else {
            // Handle 404 Not Found
            http_response_code(404);
            include __DIR__ . '/views/pages/404.php';
        }

        ?>
    </body>
</html>