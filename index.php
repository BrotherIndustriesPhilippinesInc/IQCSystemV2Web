<?php
session_start();

// --- PHP LOGIC: ALWAYS CHECK PERMISSIONS ---
// Initialize default to false
$isAdmin = false;
$isSuperAdmin = false;
$position = false;
$userID = '';

// Check if the browser has the Employee Number cookie
$employeeNumber = $_COOKIE['CurrentUserEmpNo'] ?? '';

if ($employeeNumber) {
    // ALWAYS call the API to get the latest real-time permissions
    // This ensures that if you change DB permissions, it updates instantly on refresh.
    $url = "http://apbiphiqcwb01:1116/api/SystemApproverLists/ViaEmployeeNumber/{$employeeNumber}";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2); // 2 second timeout to prevent hanging
    $response = curl_exec($ch);

    if ($response !== false) {
        $apiResult = json_decode($response, true);
        
        // Update the variable based on the FRESH API result
        $isAdmin = $apiResult['isAdmin'] ?? false;
        $isSuperAdmin = $apiResult['isSuperAdmin'] ?? false;
        $position = $apiResult['position'] ?? '';
        $userID = $apiResult['id'] ??'';
        if ($userID) {
            setcookie("userId", $userID, time() + (86400 * 7), "/"); // Expires in 7 days
        }

    }
    curl_close($ch);
}

// Update the session so other files (navbar, etc) can see it too
$_SESSION['isAdmin'] = $isAdmin;
$_SESSION['isSuperAdmin'] = $isSuperAdmin;
$_SESSION['position'] = $position;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="/iqcv2/resources/logo/iqc_logo.png">

        <script src="/iqcv2/node_modules/jquery/dist/jquery.min.js"></script>

        
        <script defer src="/iqcv2/node_modules/less/dist/less.min.js"></script>

        <script defer src="/iqcv2/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

        <script defer type="module" src="/iqcv2/js/main-script.js"></script>

        <link href="https://releases.transloadit.com/uppy/v4.13.2/uppy.min.css" rel="stylesheet"/>

        <link rel="stylesheet" type="text/css" href="/iqcv2/non_module_libraries/flatpickr/dark.css">
        <script defer src="/iqcv2/non_module_libraries/flatpickr/flatpickr.js"></script>
        
        <script defer src="/iqcv2/non_module_libraries/chartjs/chart.umd.min.js"></script>
        <script defer src="/iqcv2/non_module_libraries/chartjs/chartjs-adapter-date-fns.bundle.min.js"></script>

        <script defer src="/iqcv2/non_module_libraries/datatables/datatables.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/iqcv2/non_module_libraries/datatables/datatables.min.css">

        <link rel="stylesheet" href="/iqcv2/node_modules/bootstrap/dist/css/bootstrap.css">
        <script defer src="/iqcv2/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

        <script defer src="/iqcv2/non_module_libraries/popper/popper.min.js"></script>

        <script src="/iqcv2/non_module_libraries/fontawesome/fontawesome.js" crossorigin="anonymous"></script>

        <link rel="stylesheet/less" type="text/css" href="/iqcv2/css/style.less" />

        <script>
            $(function () {
                console.log("jQuery is working!");

                const userJson = localStorage.getItem("user");
                
                // Robust Cookie Reader
                function getCookie(name) {
                    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
                    if (match) return match[2];
                }

                if (userJson) {
                    try {
                        const userData = JSON.parse(userJson);
                        // .trim() prevents infinite loops caused by hidden spaces
                        const empNo = String(userData.EmpNo || '').trim(); 
                        const currentCookie = getCookie("CurrentUserEmpNo");

                        // CHECK: Do we need to update the cookie?
                        if (empNo && currentCookie !== empNo) {
                            
                            // SAFETY VALVE: Check if we JUST tried to fix this and failed
                            if (sessionStorage.getItem("cookie_fix_attempted")) {
                                console.warn("Loop detected: Cookie update failed or blocked. Stopping reload loop.");
                                // Optional: Clear the flag so it tries again on next manual refresh
                                sessionStorage.removeItem("cookie_fix_attempted"); 
                            } else {
                                console.log("Syncing LocalStorage to Cookie and reloading...");
                                
                                // Set the cookie
                                const d = new Date();
                                d.setTime(d.getTime() + (7*24*60*60*1000)); // 7 Days
                                document.cookie = "CurrentUserEmpNo=" + empNo + ";expires=" + d.toUTCString() + ";path=/";
                                
                                // Set the Safety Flag
                                sessionStorage.setItem("cookie_fix_attempted", "true");

                                // Reload so PHP picks up the new cookie
                                location.reload(); 
                            }
                        } else {
                            // If they match, we are good. Clear the safety flag.
                            sessionStorage.removeItem("cookie_fix_attempted");
                        }
                    } catch (e) {
                        console.error("Error parsing user JSON", e);
                    }
                }
            });
        </script>

        <script>
            function getCookie(name) {
                    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
                    if (match) return match[2];
                }

            const userId = getCookie("userId");
            $.ajax({
                url: 'https://localhost:7246/api/Accounts/' + userId, 
                type: 'GET',
                contentType: 'application/json',
                success: function(response) {
                    const bgColor = response.theme; 
                    const fontColor = response.fontColor; 

                    // 1. Update LESS (For your custom elements)
                    let lessUpdates = {};
                    if (bgColor) lessUpdates['@primary-background'] = bgColor;
                    if (fontColor) lessUpdates['@tertiary-text'] = fontColor;
                    if (fontColor) lessUpdates['@primary-text'] = bgColor;
                    if (fontColor) lessUpdates['@primary-misc'] = bgColor;

                    if (Object.keys(lessUpdates).length > 0) {
                        less.modifyVars(lessUpdates).catch(err => console.error(err));
                    }

                    // 2. FORCE OVERRIDE BOOTSTRAP (The Fix)
                    if (bgColor) {
                        // We create a custom CSS string that targets the specific Bootstrap classes
                        // We use !important to guarantee we win the specificity war.
                        const bootstrapOverride = `
                            .btn-primary {
                                --bs-btn-bg: ${bgColor} !important;
                                --bs-btn-border-color: ${bgColor} !important;
                                --bs-btn-hover-bg: ${bgColor} !important;
                                --bs-btn-hover-border-color: ${bgColor} !important;
                                --bs-btn-active-bg: ${bgColor} !important;
                                --bs-btn-active-border-color: ${bgColor} !important;
                                background-color: ${bgColor} !important; /* Safety net */
                                border-color: ${bgColor} !important;     /* Safety net */
                            }
                            
                            /* Optional: If you want Outline buttons to match too */
                            .btn-outline-primary {
                                --bs-btn-color: ${bgColor} !important;
                                --bs-btn-border-color: ${bgColor} !important;
                                --bs-btn-hover-bg: ${bgColor} !important;
                                --bs-btn-active-bg: ${bgColor} !important;
                                color: ${bgColor} !important;
                                border-color: ${bgColor} !important;
                            }
                        `;

                        // Function to inject this into the head
                        updateDynamicStyles('custom-bs-override', bootstrapOverride);
                    }
                }
            });

            // --- HELPER FUNCTION (Add this outside the AJAX call) ---
            function updateDynamicStyles(styleId, cssContent) {
                // Check if the style tag already exists
                let styleTag = document.getElementById(styleId);
                
                if (!styleTag) {
                    // If not, create it
                    styleTag = document.createElement('style');
                    styleTag.id = styleId;
                    document.head.appendChild(styleTag);
                }
                
                // update the content
                styleTag.innerHTML = cssContent;
            }
        </script>
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
        $routes = [
            '/iqcv2/' => function() {
                include __DIR__ . '/views/pages/home.php';
            },
            '/iqcv2/choose_lots/inspection_result' => function() {
                include __DIR__ . '/views/pages/choose_lots/inspection_result.php';
            },
            '/iqcv2/choose_lots/inspection_history' => function() {
                if ($_SESSION['isAdmin']) {
                    include __DIR__ . '/views/pages/choose_lots/inspection_history.php';
                }
            },
            '/iqcv2/choose_lots/inspection_details' => function() {
                include __DIR__ . '/views/pages/choose_lots/inspection_details.php';
            },
            '/iqcv2/manage/users' => function() {
                include __DIR__ . '/views/pages/manage/users.php';
            },
            '/iqcv2/manage/inspection_approval' => function() {
                include __DIR__ . '/views/pages/manage/inspection_approval.php';
            },
            '/iqcv2/registration/parts_information' => function() {
                include __DIR__ . '/views/pages/registration/parts_information.php';
            },
            '/iqcv2/settings' => function() {
                include __DIR__ . '/views/pages/settings.php';
            },
            '/iqcv2/operations/machine_lot_request' => function(){
                include __DIR__ . '/views/pages/operations/machine_lot_requests.php';
            },
            '/iqcv2/operations/machine_lot_request_integrated' => function(){
                include __DIR__ . '/views/pages/operations/machine_lot_requests_integrated.php';
            }
        ];

        // Get the current path
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Check if the path exists in the routes
        if (array_key_exists($path, $routes)) {
            $routes[$path]();
        } else {
            http_response_code(404);
            include __DIR__ . '/views/pages/404.php';
        }
        ?>
    </body>
</html>