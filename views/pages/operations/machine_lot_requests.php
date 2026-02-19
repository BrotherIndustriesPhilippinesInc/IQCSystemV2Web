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
            <button class="btn btn-primary" id="export-button">Export</button>
        </div>
        <div>
            <table class="table table-hover" id="machine-lot-table">
                <thead>
                    <tr>
                        <!-- <th scope="col"></th> -->
                        <th scope="col">RELEASE NO.</th>
                        <th scope="col">PART CODE</th>
                        <th scope="col">PART NAME</th>
                        <th scope="col">VENDOR NAME</th>
                        <th scope="col">DCI NO. / OTHER NO.</th>
                        <th scope="col">QUANTITY</th>
                        
                        <th scope="col">RELEASE REASON</th>
                        <th scope="col">YELLOW CARD</th>
                        <th scope="col">CREATED BY</th>
                        <th scope="col">EXPORTED BY</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/operations/machine_lot_request.js"></script>