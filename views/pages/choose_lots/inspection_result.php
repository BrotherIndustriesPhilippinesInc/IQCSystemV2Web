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
    <!-- <h3>Inspection Result</h3> -->
    <div class="w-100 d-flex justify-content-center">
        <img src="/iqcv2/resources/images/zero-def.png" alt="Inspection Result" style="width: 75%; height: auto;">
    </div>
    
    
</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/choose_lots/inspection.js"></script>