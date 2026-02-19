<body class="p-3">
<h1>Parts Information Upload</h1>
<h5>Jinky Countermeasure</h5>
    <div class="d-flex flex-column align-items-center justify-content-center" style="height: 50vh">
        <div>
            <div>
                <div for="fileUpload" class="form-label">Step 1: Enter BIPH ID</div>
                <input type="text" id="userId">
            </div>
            <div>
                <div for="fileUpload" class="form-label">Step 2: Choose File (Parts Information Excel File)</div>
                <input type="file" id="fileUpload">
            </div>
            
            <div>
                <div for="fileUpload" class="form-label">Step 3: Click</div>
                <button type="button" class="btn btn-primary" id="uploadButton">Upload</button>
            </div>

            <div>
                <div for="fileUpload" class="form-label">Step 4: Wait for results</div>
                <div id="result" class="border p-3 mt-2" style="min-height: 100px;"></div>
            </div>
            
        </div>
        
    </div>
</body>

<script type="module" defer src="/iqcv2/js/functions/page-scripts/registration/parts_information_standalone.js"></script>