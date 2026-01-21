<?php
    global $button;
    $primaryButton = $button->primaryButton("testButton","Button");
    $uploadButton = $button->primaryButton("uploadButton","Upload");
?>

<title>IQC System</title>

<body class="bg-custom container-fluid">
    <?php 
        require_once __DIR__ . "../../components/header.php";
        require_once __DIR__ . "../../components/navbar.php";
    ?>
    <div class="container mt-4">
    <h3>Settings</h3>

        <div>
            <div>
                <h4>Background Color</h4>
                <input type="color" id="bgColorPicker" name="bgColorPicker" value="#005CAB">
            </div>
            <div>
                <h4>Font Color</h4>
                <input type="color" id="fontColorPicker" name="fontColorPicker" value="#FFFFFF">
            </div>
            <div class="btn btn-primary" id="saveColorSettings" value="Save Color Settings">Save Color Settings</div>
        </div>
    </div>
</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/settings.js"></script>