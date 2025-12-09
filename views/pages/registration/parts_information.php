<?php
    global $button;
    $primaryButton = $button->primaryButton("testButton","Button");
    $uploadButton = $button->primaryButton("uploadButton","Upload");
?>

<title>IQC System</title>

<body class="bg-custom container-fluid">
    <?php 
        require_once __DIR__ . "/../../components/header.php";
        require_once __DIR__ . '/../../components/navbar.php';
    ?>
    <h3>Parts Information</h3>

    <div class="d-flex gap-2 align-items-end">
        <div class="w-25">
            <label for="formFile" class="form-label">Upload Parts Information</label>
            <input class="form-control" type="file" id="formFile">
        </div>
        <button type="button" class="uploadButton btn btn-primary border-1 rounded-3 fw-medium " style="height: fit-content;">
            <span class="uploadButton-span btn-span ">Upload</span>
        </button>
        <a href="http://apbiphiqcwb01:8080/iqcv2/resources/templates/Parts%20Information%20Template.xlsx" class="downloadButton btn btn-primary border-1 rounded-3 fw-medium " style="height: fit-content;">
            <span class="uploadButton-span btn-span text-light">Download Template</span>
        </a>
    </div>
    

    <div id="table-container">
        <table id="partsinformation-table" class="table table-hover">
            <thead>
                <tr class= 'text-center'>
                    <th>Category</th>
                    <th>Standard Takt Time</th>
                    <th>N1 N2</th>
                    <th>Vendor Code</th>
                    <th>Part Code</th>
                    <th>Part Name</th>
                    <th>Plant</th>
                    <th>Supplier Name</th>
                    <th>Overseas Manufacturer</th>
                    <th>Model</th>
                    <th>Model Category</th>
                    <th>QM Lot Category</th>
                    <th>Status</th>
                    <th>SF Category</th>
                    <th>PS Marking</th>
                    <th>Marking</th>
                    <th>Critical Component Safety</th>
                    <th>EOL</th>
                    <th>EOL Date</th>
                    <th>JIT</th>
                    <th>Sloc</th>
                    <th>Size</th>
                    <th>Visual Dimension</th>
                    <th>Remarks</th>
                    <th>Last Update</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <div id="partsInformationAddModal" class="modal" tabindex="-1">
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

<script type="module" defer src="/iqcv2/js/functions/page-scripts/registration/parts_information.js"></script>