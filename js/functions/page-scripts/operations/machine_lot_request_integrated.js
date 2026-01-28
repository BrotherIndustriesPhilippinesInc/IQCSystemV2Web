import apiCall from '../../apiCall.js';
import {getCookie} from '../../helpers.js';
$(async function (){
    // INITIALIZE
    let releaseReasons = await populateSelect("https://localhost:7246/api/ReleaseReasons", "#releaseReason", "id", "releaseReasonName");
    let whatFors = await populateRadio("https://localhost:7246/api/WhatFors", "#whatForContainer", "whatFor", "id", "whatForName");


    // EVENTS
    $('input[name="whatFor"]').on('change', function() {
        const selectedWhatFor = $(this).val();

        // Get details of WhatFor
        const selectedWhatForDetails = whatFors.find(whatFor => whatFor.id == selectedWhatFor);
        selectedWhatForDetails ? $('#remarks').val(selectedWhatForDetails.whatForDetails) : $('#remarks').val('');
    });

    $('#submit').on('click', async function() {
        //Get user from cookie
        const createdBy = getCookie("CurrentUserEmpNo");

        const requestData = {
            "createdBy": createdBy,
            "partCode": $('#partCode').val(),
            "partName": $('#partName').val(),
            "vendorName": $('#vendorName').val(),
            "quantity": $('#quantity').val(),
            "releaseNo": $('#releaseNo').val(),
            "yellowCard": $('#yellowCard').is(':checked'),
            "dciOtherNo": $('#dciNo').val(),
            "releaseReasonId": $('#releaseReason').val(),
            "whatForId": $('input[name="whatFor"]:checked').val(),
            "remarks": $('#remarks').val()
          };

        await submitMachineLotRequest(requestData);
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
                    const response = await apiCall("https://localhost:7246/api/MachineLotRequests", "POST", data)
                        .then(res => {
                            swal.fire({
                                title: "Success",
                                text: "Machine Lot Request submitted successfully.",
                                icon: "success",
                                confirmButtonText: "OK"
                            });
                            // Optionally, reset the form here
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
        
});