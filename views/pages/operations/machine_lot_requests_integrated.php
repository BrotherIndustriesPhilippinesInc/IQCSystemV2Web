<?php
    global $button;
    $primaryButton = $button->primaryButton("testButton","Button");
?>

<title>IQC System</title>

<body class="bg-custom container-fluid">
    
    <h3>Machine Lot Request</h3>

    <div class="container">
        <div class="">
            <div >
                <?php

                    echo "<h3 class='text-center' id='partCode'></h3>";
                echo "<h5 class='text-center' id='checkLot'></h5>";
                ?>
                <h4>What For</h4>
                <div class="d-flex gap-2">
                    <ul class="list-unstyled d-flex flex-wrap gap-3" id="whatForContainer">

                    </ul>
                </div>
            </div>
            <div >
                <h4>Machine Lot Request Form</h4>
                <div class="">
                    <div class="d-flex flex-column gap-3 flex-wrap">

                        <div class="col form-floating ">
                            <input type="text" class="form-control" id="partCodeInput" placeholder="" disabled>
                            <label for="partCode">PART CODE</label>
                        </div>

                        <div class="col form-floating ">
                            <input type="text" class="form-control" id="partName" placeholder="" disabled>
                            <label for="partName">PART NAME</label>
                        </div>

                        <div class="col">
                            <select class="form-select" id="vendorName" aria-label="Default select example">
                                <option selected>VENDOR NAME</option>
                            </select>
                        </div>

                        <!-- <div class="col form-floating">
                            <input type="text" class="form-control" id="releaseNo" placeholder="">
                            <label for="releaseNo">RELEASE NO.</label>
                        </div> -->

                        <div class="col form-floating ">
                            <input type="number" class="form-control" id="quantity" placeholder="">
                            <label for="quantity">QUANTITY</label>
                        </div>

                        <div class="col form-floating">
                            <input type="text" class="form-control" id="dciNo" placeholder="">
                            <label for="dciNo">DCI NO. / OTHER NO.</label>
                        </div>

                        <div class="col ">
                            <input type="checkbox" class="form-check-input"  id="yellowCard" placeholder="">
                            <label for="yellowCard">YELLOW CARD</label>
                        </div>

                    
                        <div class="col">
                            <select class="form-select" id="releaseReason" aria-label="Default select example">
                                <option selected>RELEASE REASON</option>
                            </select>
                        </div>

                        <div class="col form-floating">
                                <input type="text" class="form-control" id="lotNumber" placeholder="LOT NUMBER">
                                <label for="lotNumber">LOT NUMBER</label>
                        </div>

                        <div class="col">
                            <textarea class="border-primary form-control" id="remarks" rows="5" cols="50" placeholder="Remarks"></textarea>
                        </div>
                        
                    </div>
                </div>

                <div class="ms-auto my-3" style="width: fit-content;">
                    <div class="btn btn-primary" id="submit">Submit</div>
                </div>
                    
            </div>
         
        </div>
    </div>

</body>

<script type="module" src="/iqcv2/js/functions/page-scripts/operations/machine_lot_request_integrated.js"></script>