<?php
    global $button;
    $primaryButton = $button->primaryButton("testButton","Button");

    $whatForOptions = [
        "moldTrial" => "MOLD TRIAL",
        "prodTrial" => "PROD. TRIAL",
        "massProd" => "MASS PROD.",
        "newParts" => "NEW PARTS",
        "testRun" => "TEST RUN",
        "4M"=> "4M",
        "SA"=> "SA",
        "sortedLot"=> "SORTED LOT",
        "correctedLot"=> "CORRECTED LOT",
        "others"=> "OTHERS",
    ];
?>

<title>IQC System</title>

<body class="bg-custom container-fluid">

    <h3>Machine Lot Request</h3>

    <div class="container">
        <div class="">
            <div >
                <h4>What For</h4>
                <div class="d-flex gap-2">
                    <ul class="list-unstyled d-flex flex-wrap gap-3">
                        <?php 
                        $count = 0;
                        foreach($whatForOptions as $key => $value){

                            echo "<li class='form-check'>
                                <input class='form-check-input' type='checkbox' id='{$key}' name='{$key}' value='{$value}'> 
                                <label for='{$key}'>{$value}</label>
                            </li>";
                            $count++;
                        }
                        ?>
                    </ul>
                </div>
                
            </div>
            <div >
                <h4>Machine Lot Request Form</h4>
                <div class="">
                    <div class="d-flex flex-column gap-2 flex-wrap">

                        <div class="col form-floating mb-2">
                            <input type="text" class="form-control" id="partCode" placeholder="">
                            <label for="partCode">PART CODE</label>
                        </div>

                        <div class="col form-floating">
                            <input type="text" class="form-control" id="releaseReason" placeholder="">
                            <label for="releaseReason">RELEASE REASON</label>
                        </div>

                        <div class="col form-floating mb-2">
                            <input type="text" class="form-control" id="partName" placeholder="">
                            <label for="partName">PART NAME</label>
                        </div>

                        <div class="col form-floating">
                            <input type="text" class="form-control" id="releaseNo" placeholder="">
                            <label for="releaseNo">RELEASE NO.</label>
                        </div>

                        <div class="col form-floating mb-2">
                            <input type="text" class="form-control" id="vendorName" placeholder="">
                            <label for="vendorName">VENDOR NAME</label>
                        </div>

                        <div class="col form-floating mb-2">
                            <input type="text" class="form-control" id="quantity" placeholder="">
                            <label for="quantity">QUANTITY</label>
                        </div>

                        <div class="col">
                            <input type="checkbox" class="form-check-input"  id="yellowCard" placeholder="">
                            <label for="yellowCard">YELLOW CARD</label>
                        </div>

                        <div class="col form-floating">
                            <input type="text" class="form-control" id="dciNo" placeholder="">
                            <label for="dciNo">DCI NO. / OTHER NO.</label>
                        </div>

                        <div class="mt-2">
                            <select class="form-select mb-3" aria-label="Default select example">
                                <option selected>WHAT FOR???</option>
                                <option value="1">TYPE 1</option>
                                <option value="2">TYPE 2</option>
                                <option value="3">TYPE 3</option>
                            </select>
                            <textarea class="border-primary form-control" rows="5" cols="50" placeholder="Remarks"></textarea>
                        </div>

                    </div>
                </div>

                <div class="ms-auto my-3" style="width: fit-content;">
                    <div class="btn btn-primary">Submit</div>
                </div>
                    
            </div>
         
        </div>
    </div>

</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/operations/machine_lot_requests_integrated.js"></script>