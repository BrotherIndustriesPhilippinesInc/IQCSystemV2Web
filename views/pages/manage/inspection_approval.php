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
    <h3>Inspection Approval</h3>

    <div id="table-container">
        <table id="approval-table" class="table table-hover">
            <thead>
                <tr class= 'text-center'>
                    <th scope="col">Stock-In Date</th>
                    <th scope="col">Inspection Date</th>
                    <th scope="col">Check Lot</th>
                    <th scope="col">Partcode</th>
                    <th scope="col">Production Lot No.</th>
                    <th scope="col">Inspector</th>
                    <th scope="col">Supervisor</th>
                    <th scope="col">Remarks</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <div id="userAddModal" class="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="employeeNumber" class="form-label text-primary-misc">Employee Number</label>
                <input type="text" class="form-control" id="employeeNumber" placeholder="Ex: BIPH2019-03260">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="saveUser" type="button" class="btn btn-primary">Save</button>
            </div>
            </div>
        </div>
    </div>

</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/inspection_approval/inspection_approval.js"></script>