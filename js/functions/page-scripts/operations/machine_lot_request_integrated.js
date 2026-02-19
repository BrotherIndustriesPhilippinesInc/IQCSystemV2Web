import apiCall from '../../apiCall.js';
import {getCookie} from '../../helpers.js';
import { sendToWebView, receiveFromWebView } from '../../WebViewInteraction.js';
$(async function (){
    // INITIALIZE
    // Call this ONCE when the page loads
    receiveFromWebView("machineLotRequestDone", (data) => {
        console.log("Callback triggered with:", data);

        if (data.actionName === "machineLotRequestDone") {
            swal.fire({
                title: "Success",
                text: "Machine Lot Request submitted successfully.",
                icon: "success" // Don't forget the icon!
            });
        }
    });

    let releaseReasons = await populateSelect("http://apbiphiqcwb01:1116/api/ReleaseReasons", "#releaseReason", "id", "releaseReasonName");
    let whatFors = await populateRadio("http://apbiphiqcwb01:1116/api/WhatFors", "#whatForContainer", "whatFor", "id", "whatForName");

    const partCode = $("#partCode").text().trim();
    let partDetails = await populateSelect("http://apbiphiqcwb01:1116/api/PartsInformations/partcode?partCode=" + partCode, "#vendorName", "id", "supplierName");

    await fetchPartCodeDetails();
    // EVENTS
    $('input[name="whatFor"]').on('change', function() {
        const selectedWhatFor = $(this).val();

        // Get details of WhatFor
        const selectedWhatForDetails = whatFors.find(whatFor => whatFor.id == selectedWhatFor);
        selectedWhatForDetails ? $('#remarks').val(selectedWhatForDetails.whatForDetails) : $('#remarks').val('');

        // Get ReleaseReason
        // const selectedReleaseReason = releaseReasons.find(reason => reason.id == selectedWhatForDetails.releaseReasonId);
        // selectedReleaseReason ? $('#releaseReason').val(selectedReleaseReason.id) : $('#releaseReason').val('');

        // Get Vendor
        // const selectedVendor = partDetails.find(vendor => vendor.id == selectedWhatForDetails.vendorId);
        // selectedVendor ? $('#vendorName').val(selectedVendor.id) : $('#vendorName').val('');
    });

    $('#submit').on('click', async function() {
        //Get user from cookie
        const createdBy = getCookie("CurrentUserEmpNo");

        const requestData = {
            "createdBy": createdBy,
            "partCode": $('#partCodeInput').val(),
            "partName": $('#partName').val(),
            "vendorName": $('#vendorName option:selected').text(),
            "quantity": $('#quantity').val(),
            // "releaseNo": $('#releaseNo').val(),
            "yellowCard": $('#yellowCard').is(':checked'),
            "dciOtherNo": $('#dciNo').val(),
            "releaseReasonId": $('#releaseReason').val(),
            "whatForId": $('input[name="whatFor"]:checked').val(),
            "remarks": $('#remarks').val(),
            "checkLot": $('#checkLot').text().trim(),

            "lotNumber": $('#lotNumber').val().trim()
        };

        //check all required fields 
        if (!requestData.partCode || !requestData.vendorName || !requestData.quantity || !requestData.releaseReasonId || !requestData.whatForId) {
            alert("Please fill in all required fields.");
            return;
        }

        await submitMachineLotRequest(requestData);
    });

    $("#lotNumber").on("input", function () {
        const lotNumber = $(this).val().trim();

        $('#remarks').val((i, currentVal) => {
            // Regex logic:
            // ^LOT NO\s*:\s*.*$ 
            // ^ matches start of line (with 'm' flag)
            // .* matches everything until the end of that line
            const regex = /^LOT NO\s*:\s*.*$/m;
            const newLine = `LOT NO : ${lotNumber}`;

            // If the line exists, replace it. If not, append it.
            if (regex.test(currentVal)) {
                return currentVal.replace(regex, newLine);
            } else {
                return currentVal + "\n" + newLine;
            }
        });
    });

    

    
    // FUNCTIONS
    async function populateSelect(url, selectElementId, valueField, textField) {
        let releaseReasons = await apiCall(url, 'GET');

        const releaseReasonSelect = $(selectElementId);

        releaseReasons.forEach(reason => {
            const option = $('<option></option>')
                .attr('value', reason[valueField])
                .text(reason[textField]);
            releaseReasonSelect.append(option);
        });

        return releaseReasons;
    }

    async function populateRadio(url, containerSelector, groupName, valueField, textField) {
        // 1. Fetch data
        let dataList = await apiCall(url, 'GET');

        // 2. Cache the container element so we don't search the DOM inside the loop
        const $container = $(containerSelector);

        // Optional: Clear previous items to prevent duplicates if called twice
        $container.empty();

        dataList.forEach(item => {
            // 3. Create a unique ID for the label to target
            // We combine groupName + value to ensure it's unique on the page
            const uniqueId = `${groupName}_${item[valueField]}`;

            // 4. Use Template Literals for clean HTML
            const radioHtml = `
            <div class="form-check">
                <input class="form-check-input" type="radio" 
                       name="${groupName}" 
                       id="${uniqueId}" 
                       value="${item[valueField]}">
                <label class="form-check-label" for="${uniqueId}">
                    ${item[textField]}
                </label>
            </div>
        `;

            $container.append(radioHtml);
        });

        return dataList;
    }

    async function submitMachineLotRequest(data) {
        try {
            //confirmation
            swal.fire({
                title: "Confirm Submission",
                text: "Are you sure you want to submit this Machine Lot Request?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, submit it!",
                cancelButtonText: "Cancel"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    //loading
                    swal.fire({
                        title: "Submitting...",
                        text: "Please wait while we submit your request.",
                        allowOutsideClick: false,
                        didOpen: async () => {
                            swal.showLoading();
                            
                            try {
                                
                                const response = await apiCall("http://apbiphiqcwb01:1116/api/MachineLotRequests", "POST", data)
                                //const response = await apiCall("https://localhost:7246/api/MachineLotRequests", "POST", data)
                                    .then(res => {
                                        //Add checklot to res
                                        res.checkLot = data.checkLot;
                                        
                                        sendToWebView("SubmitMachineLotRequest", {
                                            data: res
                                        });
                                    });

                            } catch (error) {
                                swal.fire({
                                    title: "Error",
                                    text: "Failed to submit Machine Lot Request. Please try again.",
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        }
                    });
                }
            });
            
            
        } catch (error) {
            console.error("Failed to submit Machine Lot Request:", error);
            swal.fire({
                title: "Error",
                text: "Failed to submit Machine Lot Request. Please try again.",
                icon: "error",
                confirmButtonText: "OK"
            });
            throw error;
        }
    };

    async function fetchPartCodeDetails() {
        try {
            const details = partDetails[0];
            if (details) {

                $('#partCodeInput').val(details.partCode);
                $('#partName').val(details.partName);

            }else{

                swal.fire({
                    title: "Not Found",
                    text: `No details found for Part Code: ${partCode}`,
                    icon: "warning",
                    confirmButtonText: "OK"
                });

            }
        } catch (error) {
            console.error("Error in fetchPartCodeDetails:", error);
        }
    }

    
});