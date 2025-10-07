                    //Receive from webview
                    function receiveFromWebView(callback, executeOnce = false) {
                        return new Promise((resolve, reject) => {
                            window.chrome.webview.addEventListener(
                                'message',
                                function (event) {
                                    console.log("Received message from C#: ", event.data);
                                    let data = JSON.parse(event.data);

                                    if (data["actionName"] === "error") {
                                        errorTracker(data["actionName"], data.data.message);
                                    } else {
                                        // Execute the provided callback function with the received data
                                        if (typeof callback === 'function') {
                                            callback(data);
                                        }
                                    }

                                    resolve(data);
                                },
                                { once: executeOnce } // Automatically removes the listener after being called once
                            );
                        });
                    }

                    //Send to webview
                    function sendToWebView(actionName,data = {}) {
                        if (window.chrome && window.chrome.webview) {
                            let payload = {
                                actionName: actionName,
                                data
                            }
                            let payloadString = JSON.stringify(payload);
                            window.chrome.webview.postMessage(payloadString);
                        } else {
                            console.warn("WebView2 not available. Ensure you're running in a WebView2 environment.");
                        }
                    }


                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    //GENERAL VARIABLES
                    let startTime = 0;
                    let timerInterval = null;

                    // Get item list
                    let part_code = document.getElementById("txtMCodeQuery").value
                    let items;
                    async function getAllItems(){
                        let data;
                        sendToWebView("GetList", {part_code});
                        await receiveFromWebView((e)=>{
                            data = e;
                            // PROCESS or functions
                            
                        });
                        return data;
                    }
                    
                    ////////////////////////////// MAIN FUNCTION ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    
                    (async () => {
                        

                        items = await getAllItems();
                        console.log("Got items:", items);

                        startTimer();

                        // Map your button IDs to category keys
                        const categoryMap = {
                            threeD: "threeD",
                            twoD: "2d",
                            wi: "wi",
                            artwork: "artwork",
                            dci: "dci",
                            ng: "ng",
                            qhc: "qhc"
                        };

                        Object.entries(categoryMap).forEach(([btnId, category]) => {
                            let btn = document.getElementById(btnId);
                            if (!items[category] || !items[category][category] || items[category][category].length === 0) {
                                btn.setAttribute("disabled", "true");
                                btn.classList.add("btn-secondary");  // make it grey
                                btn.classList.remove("btn-primary"); // remove active color
                            }
                        });

                        

                        //Attach events to ALL new buttons
                        document.getElementById("cmdSave").addEventListener('click', function () {
                            insertInspectionData();
                        });

                        
                    })();

                    //////////////////// FUNCTIONS ///////////////////////////////////////////////////////////////////////////////

                    function generateItems(category) {
                        let container = document.getElementById('items-container');
                        container.innerHTML = ''; 
                        container.className = "d-flex gap-2 flex-wrap mt-2";

                        items[category][category].forEach(item => {
                            let div = document.createElement('div');
                            div.innerHTML = `
                                <button class="iqc-items btn btn-info" 
                                        data-category="${category}" 
                                        data-item="${item.fileName}">
                                    ${item.fileName}
                                </button>
                            `;
                            container.appendChild(div);
                        });

                        // Attach events to ALL new buttons
                        document.querySelectorAll('.iqc-items').forEach(btn => {
                            btn.addEventListener('click', function () {
                                let category = this.getAttribute('data-category');
                                let fileName = this.getAttribute('data-item');
                                console.log(`${category} & ${fileName} clicked`);
                                if (category == "threeD") 
                                {
                                    openThreeD(fileName);      
                                }else{
                                    openItems(category, fileName);
                                }
                            });
                        });
                    }

                    //Open 3D
                    function openThreeD(fileName) {
                        sendToWebView("OpenThreeD", {fileName});
                    }


                    //Open Items
                    function openItems(category, fileName) {
                        let container = document.getElementById('items-container');

                        // Build the file path (adjust if using http or file://)
                        let pdfUrl = `http://apbiphbpsts01:8080/iqc/resources/${category}/${fileName}`;
                        pdfUrl = pdfUrl.replace("xlsm", "pdf");
                        pdfUrl = pdfUrl.replace("xlsx", "pdf");

                        // Clear old content
                        container.innerHTML = '';

                        // Embed the PDF viewer
                        container.innerHTML = `
                            <div class="w-100" style="height:50vh;">
                                <embed src="${pdfUrl}" type="application/pdf" width="100%" height="100%" />
                            </div>
                        `;
                    }

                    // Hook buttons to generator
                    document.getElementById('threeD').addEventListener('click', () => generateItems('threeD'));
                    document.getElementById('twoD').addEventListener('click', () => generateItems('twoD'));
                    document.getElementById('wi').addEventListener('click', () => generateItems('wi'));
                    document.getElementById('artwork').addEventListener('click', () => generateItems('artwork'));
                    document.getElementById('dci').addEventListener('click', () => generateItems('dci'));
                    document.getElementById('ng').addEventListener('click', () => generateItems('ng'));
                    document.getElementById('qhc').addEventListener('click', () => generateItems('qhc'));

                    document.getElementsByClassName('iqc-items')[0].addEventListener('click', function () {
                        
                        let category = this.getAttribute('data-category');
                        let fileName = this.getAttribute('data-item');
                        console.log(`${category} & ${fileName} clicked`);
                        openItems(category, fileName);
                    });

                    function back(){
                        window.history.go(-2);
                    }

                    //Timer Function
                    function startTimer() {
                        startTime = Date.now();

                        // Clear any existing interval
                        if (timerInterval) clearInterval(timerInterval);

                        // Update every second
                        timerInterval = setInterval(() => {
                            const elapsedMinutes = Math.floor((Date.now() - startTime) / 60000); // whole minutes
                            const txt = document.getElementById("txtCheckTime");
                            if (txt) txt.value = elapsedMinutes; // update with whole minutes
                        }, 1000);
                    }

                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    function disableSubmitButton(){
                        document.getElementById("cmdSubmit").disabled = true;
                    }
                    async function getInspectionData() {
                        try {
                            const response = await fetch('http://apbiphiqcwb01/IQCSystemAPI/API/InspectionDetails', {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json'
                                }
                            });

                            if (!response.ok) {
                                console.error("HTTP error:", response.status);
                                return;
                            }

                            const data = await response.json(); // parse JSON response
                            console.log("Received data:", data);

                        } catch (err) {
                            console.error("Fetch error:", err);
                        }
                    }

                    async function insertInspectionData(){
                        let data = {
                            "checkLot": document.getElementById("txtIqcLotQuery").value,
                            "dimenstionsMaxSamplingCheckQty": document.getElementById("txtMaxNumEdit").value,
                            "continuedEligibility": document.getElementById("txtQualifiedLotNo").value,
                            "relatedCheckLot": document.getElementById("txtUnitIQCLot").value,

                            "stockInCollectDate": document.getElementById("txtInDateQuery").value,
                            "partCode": document.getElementById("txtMCodeQuery").value,
                            "samplingCheckQty": document.getElementById("txtCheckQty").value,

                            "factoryCode": document.getElementById("txtFacCodeQuery").value,
                            "partName": document.getElementById("txtMNameQuery").value,
                            "allowQty": document.getElementById("txtAllowQty").value,    

                            "standard": document.getElementById("txtStandard").value,
                            "totalLotQty": document.getElementById("txtLotinQty").value,
                            "samplingRejectQty": document.getElementById("txtRejectSize").value,



                            "iqcCheckDate": document.getElementById("txtIQCCheckDate_GuruDate").value,
                            "classOne": document.getElementById("ddlFirstClass").value,
                            "samplingCheckDefectiveQty": document.getElementById("txtNgQty").value,
                            "lotJudge": document.getElementById("ddlLotResult").value,
                            "occuredEngineer": document.getElementById("ddlOccEng").value,
                            "checkMonitor": document.getElementById("ddlMonitorCheckEdit").value,

                            "lotNo": document.getElementById("txtLotNo").value,
                            "classTwo": document.getElementById("ddlSecondClass").value,
                            "rejectQty": document.getElementById("txtRejectQty").value,
                            "processMethod": document.getElementById("txtTreatmentMeas").value,
                            "checkUser": document.getElementById("txtCheckUser").value,

                            "proficienceLevel": document.getElementById("ddlProLevel").value,
                            "firstSize": document.getElementById("txtFirstSize").value,
                            "secondSize": document.getElementById("txtSecondSize").value,
                            "supervisor": document.getElementsByName("txtSupervisor$ctl00")[0].value,
                            "modelNo": document.getElementById("txtModelNo").value,
                            "designNoticeNo": document.getElementById("txtDESIGNNOTICE").value,

                            "firstAppearance": document.getElementById("txtFirstAppear").value,
                            "secondAppearance": document.getElementById("txtSecondAppear").value,
                            "actualCheckTime": document.getElementById("txtCheckTime").value,
                            "fourMNumber": document.getElementById("txtPowerofatt").value,
                            "remarks": document.getElementById("txtMemoEdit").value,

                            "outgoingInspectionReport": document.getElementById("ddlIndustry").value,
                            "threeCDataConfirm": document.getElementById("ddlValidity").value,

                            
                            "createdBy": '" + this.userID+ @"',

                            "visualCheckItems": document.getElementById("gridWebGridDiv").outerHTML,
                            "dimensionCheckItems": document.getElementById("gridWebGrid1Div").outerHTML
                        };

                    await fetch('http://apbiphiqcwb01:1116/API/InspectionDetails', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        }).catch(err => alert(err));
                    }

                    function getCheckItems(){
                        let outer = document.getElementById("checkResultBodyTable").outerHTML;
                        return outer;

                    }

                