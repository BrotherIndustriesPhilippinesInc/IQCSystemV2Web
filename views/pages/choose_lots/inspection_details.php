<?php
    global $button;
    global $textbox;
    global $select;
    
    $primaryButton = $button->primaryButton("testButton","Button");

    $visualButton = $button->primaryButton("visualButton","Visual Inspection", "","","data-bs-toggle='offcanvas' data-bs-target='#visualCanvas' aria-controls='offcanvasRight'", "primary-background tertiary-text");
    $dimensionButton = $button->primaryButton("dimensionButton","Dimension Inspection","","", "data-bs-toggle='offcanvas' data-bs-target='#dimensionCanvas' aria-controls='offcanvasRight'", "primary-background");
    $finalButton = $button->primaryButton("finalButton","Final Inspection","","", "data-bs-toggle='offcanvas' data-bs-target='#finalCanvas' aria-controls='offcanvasRight'", "primary-background");

?>

<title>IQC System</title>

<body class="bg-custom container-fluid">
    <?php 
        require_once __DIR__ . "/../../components/header.php";
        require_once __DIR__ . '/../../components/navbar.php';
    ?>
    <div class="d-flex align-items-center gap-2 mb-2">
        <h3 class="m-0">Inspection Details - Partcode</h3>
        
        <div id="generalInfo-trigger-container" class="fs-3 pointer"  data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fa-solid fa-circle-info"></i>
        </div>
    </div>

    <!-- TABS -->
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-yellow_card" data-bs-toggle="tab" data-bs-target="#nav-yellow_card-content" type="button" role="tab" aria-controls="nav-yellow_card" aria-selected="true">Yellow Card</button>
        <button class="nav-link" id="nav-3d" data-bs-toggle="tab" data-bs-target="#nav-3d-content" type="button" role="tab" aria-controls="nav-3d" aria-selected="false">3D</button>
        <button class="nav-link" id="nav-2d" data-bs-toggle="tab" data-bs-target="#nav-2d-content" type="button" role="tab" aria-controls="nav-2d" aria-selected="false">2D</button>
        <button class="nav-link" id="nav-wi" data-bs-toggle="tab" data-bs-target="#nav-wi-content" type="button" role="tab" aria-controls="nav-wi" aria-selected="false">Work Instructions</button>
        <button class="nav-link" id="nav-artwork" data-bs-toggle="tab" data-bs-target="#nav-artwork-content" type="button" role="tab" aria-controls="nav-artwork" aria-selected="false">Artwork</button>
        <button class="nav-link" id="nav-dci" data-bs-toggle="tab" data-bs-target="#nav-dci-content" type="button" role="tab" aria-controls="nav-dci" aria-selected="false">DCI</button>
        <button class="nav-link" id="nav-ng" data-bs-toggle="tab" data-bs-target="#nav-ng-content" type="button" role="tab" aria-controls="nav-ng" aria-selected="false">NG Illustrations</button>
        <button class="nav-link" id="nav-qhc" data-bs-toggle="tab" data-bs-target="#nav-qhc-content" type="button" role="tab" aria-controls="nav-qhc" aria-selected="false">Quality History Cards</button>

    </div>
    <!-- TAB CONTENT -->
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-yellow_card-content" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
            <div>yellow</div>
        </div>
        <div class="tab-pane fade" id="nav-3d-content" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
            <div>3d</div>
        </div>
        <div class="tab-pane fade" id="nav-2d-content" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
            <div class="position-absolute top-32">
                <input class="form-control form-input" type="select">
            </div>
            <div>
                <iframe 
                    src="/iqcv2/non_module_libraries/pdfjs/web/viewer.html?file=/iqcv2/resources/pdfs/2d/LAJ256J.PDF"
                    width="100%" 
                    height="100%"
                    style="border: none;">
                </iframe>
            </div>
            
        </div>
        <div class="tab-pane fade" id="nav-wi-content" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">
            <div>wi</div>
        </div>
        <div class="tab-pane fade" id="nav-artwork-content" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">
            <div>art</div>
        </div>
        <div class="tab-pane fade" id="nav-dci-content" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">
            <div>dci</div>
        </div>
        <div class="tab-pane fade" id="nav-ng-content" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">
            <div>ng</div>
        </div>
        <div class="tab-pane fade" id="nav-qhc-content" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">
            <div>qhc</div>
        </div>
    </div>

    <!-- General Info Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">General Information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
    </div>

    <!-- CANVAS TRIGGERS -->
    <div class="step-buttons-container d-flex flex-column gap-1 position-absolute top-40 star-0">
        <?php echo $visualButton; ?>
        <?php echo $dimensionButton; ?>
        <?php echo $finalButton; ?>
    </div>

    <!-- VISUAL INSPECTION CANVAS -->
    <div class="offcanvas offcanvas-end w-25" tabindex="-1" id="visualCanvas" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="visualCanvasTitle">Visual Inspection</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="check-items-container d-flex flex-column gap-3">
                <!-- VISUAL CHECK ITEMS -->
            </div>

        </div>
    </div>

    <!-- DIMENSION INSPECTION CANVAS -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="dimensionCanvas" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="dimensionCanvasTitle">Dimension Inspection</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="check-items-container d-flex flex-column gap-3">
                <!-- DIMENSION INSPECTION ITEMS -->
            </div>
        </div>
    </div>

    <!-- FINAL INSPECTION CANVAS -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="finalCanvas" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="finalCanvasTitle">Final Inspection</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="final-inspection-details d-flex flex-column gap-3">
                <div>
                    <input class="form-control form-input date-input" type="date" value="{$date}">
                </div>
                <div class="class">
                    $class
                </div>
                <div class="d-flex justify-content-between gap-2">
                    $totalDefect
                    $user
                </div>
                
                <div class="d-flex gap-2">
                    <div class="lotjudge">
                        $lotJudge
                    </div>
                    <div class="lotnumber">
                        $lotNumber
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <div class="firstsize">
                        $firstSize
                    </div>
                    <div class="secondsize">
                        $secondSize
                    </div>
                </div>
                
                
                <div class="supervisor">
                    $superVisor
                </div>

                <div class="d-flex gap-2">
                    <div class = "firstappearance">
                        $firstAppearance
                    </div>
                    <div class = "secondappearance">
                        $secondAppearance
                    </div>
                    <div class = "actualchecktime">
                        $actualCheckTime
                    </div>
                </div>
                
                
                <div class = "remarks">
                    $remarks
                </div>
                <div class="d-flex justify-content-evenly">
                    $save
                    $submit
                </div>
                
            </div>
            
        </div>
    </div>
    
    <!-- TIME -->
    <div class="time-container gap-3 position-absolute p-3 rounded bottom-0 start-0">
        <div class="container time" style="white-space:nowrap;">
            <div class="row align-items-start">
                <div class="col">
                    $st
                </div>
                <div class="col">
                    $n1n2
                </div>
                
                <div class="col">
                    $visualTime
                </div>

                <div  class="col">
                    $measurmentTime
                </div>

                <div  class="col">
                    $overallTime
                </div>
            </div>
        </div>
        $pause
        $play
        $timeCollapse
        $timeExpand
    </div>
</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/choose_lots/inspection_details.js"></script>