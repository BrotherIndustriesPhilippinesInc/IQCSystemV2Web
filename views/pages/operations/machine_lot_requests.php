<?php
    global $button;
    $primaryButton = $button->primaryButton("testButton","Button");

?>

<title>IQC System</title>

<body class="bg-custom container-fluid">
    <?php 
        require_once __DIR__ . "/../../components/header.php";
        require_once __DIR__ . '/../../components/navbar.php';
    ?>
    <h3>Machine Lot Request</h3>

    <div>
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">PART CODE</th>
                        <th scope="col">PART NAME</th>
                        <th scope="col">VENDOR NAME</th>
                        <th scope="col">DCI NO. / OTHER NO.</th>
                        <th scope="col">QUANTITY</th>
                        <th scope="col">RELEASE NO.</th>
                        <th scope="col">RELEASE REASON</th>
                        <th scope="col">YELLOW CARD</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/inspection_approval/inspection_approval.js"></script>