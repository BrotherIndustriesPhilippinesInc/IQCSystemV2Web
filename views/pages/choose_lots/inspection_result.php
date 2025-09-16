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
    <h3>Inspection Result</h3>
    <!-- <div id="table-container">
    <table id="inspection-table" class="table table-hover">
        <thead>
            <tr class= 'text-center'>
                <th scope="col"></th>
                <th scope="col">Factory Code</th>
                <th scope="col">Stock In Date</th>
                <th scope="col">State</th>
                <th scope="col">Check Lot</th>
                <th scope="col">Category</th>
                <th scope="col">Part Code</th>
                <th scope="col">Vendor Code</th>
                <th scope="col">Lot in Quantity</th>
                <th scope="col">AOQL Standard</th>
                <th scope="col">Sampling Level</th>
                <th scope="col">Samples Quantity</th>
                <th scope="col">Production Lot Number</th>
                <th scope="col">Lot Judge</th>
                <th scope="col">Model</th>
                <th scope="col">Issue Number</th>
                <th scope="col">Outgoing Inspect</th>
                <th scope="col">3C Data Confirm</th>
                <th scope="col">Print Status</th>
                <th scope="col">Related Checklots</th>
                <th scope="col">Inspector</th>
                <th scope="col">Timestamp</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
    </div> -->
    
</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/choose_lots/inspection.js"></script>