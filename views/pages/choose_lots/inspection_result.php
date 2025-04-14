<?php
    global $button;
    $primaryButton = $button->primaryButton("testButton","Button");
?>

<title>IQC System</title>

<body class="bg-custom text-light container-fluid">
    <?php 
        require_once __DIR__ . "/../../components/header.php";
        require_once __DIR__ . '/../../components/navbar.php';
    ?>
    
    <table id="inspection-table" class="table" style="width:100%">
        <thead>
            <tr class= 'text-center'>
                <th></th>
                <th>Factory Code</th>
                <th>Stock In Date</th>
                <th>State</th>
                <th>Check Lot</th>
                <th>Category</th>
                <th>Part Code</th>
                <th>Vendor Code</th>
                <th>Lot in Quantity</th>
                <th>AOQL Standard</th>
                <th>Sampling Level</th>
                <th>Samples Quantity</th>
                <th>Production Lot Number</th>
                <th>Lot Judge</th>
                <th>Model</th>
                <th>Issue Number</th>
                <th>Outgoing Inspect</th>
                <th>3C Data Confirm</th>
                <th>Print Status</th>
                <th>Related Checklots</th>
                <th>Inspection Status</th>
                <th>Inspector</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</body>
<script type="module" defer src="/iqcv2/js/functions/page-scripts/choose_lots/inspection.js"></script>