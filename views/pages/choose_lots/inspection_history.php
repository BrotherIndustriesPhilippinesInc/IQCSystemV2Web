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
    <h3>Inspection History</h3>

    <div id="table-container">
        <table id="history-table" class="table table-hover">
            <thead>
                <tr class='text-center'>
                    <th scope="col">Stock-In Date</th>
                    <th scope="col">Inspection Date</th>
                    <th scope="col">Check Lot</th>
                    <th scope="col">Partcode</th>
                    <th scope="col">Production Lot No.</th>
                    <th scope="col">Inspector</th>
                    <th scope="col">Supervisor</th>
                    <th scope="col">Approver</th>
                    <th scope="col">Remarks</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/choose_lots/inspection_history.js"></script>