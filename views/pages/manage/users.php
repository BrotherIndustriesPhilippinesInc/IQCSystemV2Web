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
    <h3>Users</h3>
    <div>
        <button class="btn btn-primary" id="addUserBtn" data-bs-toggle="modal" data-bs-target="#userAddModal">Add User</button>
    </div>
    <div id="table-container">
        <table id="inspection-table" class="table table-hover">
            <thead>
                <tr class= 'text-center'>
                    <th scope="col">Employee Number</th>
                    <th scope="col">Name</th>
                    <th scope="col">ADID</th>
                    <th scope="col">Mes Name</th>
                    <th scope="col">Email Address</th>
                    <th scope="col">Section</th>
                    <th scope="col">Position</th>
                    <th scope="col">Is Admin</th>
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
                <div class="mb-3">
                    <label for="employeeNumber" class="form-label text-primary-misc">Employee Number</label>
                    <input type="text" class="form-control" id="employeeNumber" placeholder="Ex: BIPH2019-03260">
                </div>

                <div class="mb-3">
                    <label for="emesName" class="form-label text-primary-misc">EMES Name</label>
                    <input type="text" class="form-control" id="emesName" placeholder="Ex: K.Talibutab (Be careful of spacing)">
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="isAdmin">
                    <label class="form-check-label" for="isAdmin">
                        Is Admin
                    </label>
                </div>

                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="saveUser" type="button" class="btn btn-primary">Save</button>
            </div>
            </div>
        </div>
    </div>

    <div id="userUpdateModal" class="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="updateEmployeeNumber" class="form-label text-primary-misc">Employee Number</label>
                    <input type="text" class="form-control" id="updateEmployeeNumber" placeholder="Ex: BIPH2019-03260" disabled>
                </div>

                <div class="mb-3">
                    <label for="updateEmesName" class="form-label text-primary-misc">EMES Name</label>
                    <input type="text" class="form-control" id="updateEmesName" placeholder="Ex: K.Talibutab (Be careful of spacing)">
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="updateIsAdmin">
                    <label class="form-check-label" for="updateIsAdmin">
                        Is Admin
                    </label>
                </div>
            </div>
            
            <div class="modal-footer">
                <input type="hidden" id="updateUserId" value="">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="updateUser" type="button" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/users/users.js"></script>