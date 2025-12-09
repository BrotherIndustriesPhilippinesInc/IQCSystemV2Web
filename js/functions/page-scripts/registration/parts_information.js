import dataTablesInitialization from "../../dataTablesInitialization.js";
import apiCall  from "../../apiCall.js";

$(function(){

    /* INIT */
    let user = JSON.parse(localStorage.getItem("user"));
    let userId = user["EmpNo"];

    const tableParams = {
    ajax: {
        url: "http://apbiphiqcwb01:1116/api/PartsInformations/datatables",
        method: "POST",
        contentType: 'application/json',
        data: function (d) {
            return JSON.stringify(d);
        }
    },
    processing: true,
    serverSide: true,
    layout: {
        topEnd: ['search', 'pageLength'],
        topStart: {
            buttons: ['colvis']
        }
    },
    columns: [
        { data: 'category', visible: false},
        { data: 'standardTaktTime', visible: false },
        { data: 'n1N2', visible: false },
        { data: 'vendorCode' },
        { data: 'partCode' },
        { data: 'partName' },
        { data: 'plant' },
        { data: 'supplierName' },
        { data: 'overseasManufacturer', visible: false },
        { data: 'model' },
        { data: 'modelCategory' },
        { data: 'qM_Lot_Category', visible: false },          // corrected from QmLotCategory
        { data: 'inspectionStatus' },       // corrected from Status
        { data: 'sfCategory', visible: false },
        { data: 'psMark', visible: false },                 // corrected from PsMarking
        { data: 'markings', visible: false },               // corrected from Marking
        { data: 'criticalComponentSafety', visible: false },
        { data: 'eol', visible: false },                     // corrected from Eol
        { data: 'eolDate', visible: false },                 // corrected from EolDate
        { data: 'jit', visible: false },                     // corrected from Jit
        { data: 'sloc', visible: false },
        { data: 'size', visible: false },
        { data: 'visualDimension', visible: false },
        { data: 'remarks' },
        { data: 'lastUpdate', visible: false }
    ],

    };
    let table = dataTablesInitialization("#partsinformation-table", tableParams);



    /* EVENTS */
    $(".uploadButton").on("click", function() {
    let user = JSON.parse(localStorage.getItem("user"));

        Swal.fire({
            title: 'Are you sure you want to upload this file?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, upload it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData();
                formData.append('file', $('#formFile')[0].files[0]);

                Swal.fire({
                    title: 'Uploading...',
                    html: 'Please wait while the file is being uploaded.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading(); // Show loading spinner
                    }
                });

                apiCall(`http://apbiphiqcwb01:1116/api/PartsInformations/upload?username=${userId}`, 'POST', formData, true)
                //apiCall(`https://localhost:7246/api/PartsInformations/upload?username=${userId}`, 'POST', formData, true)
                .then(response => {
                    Swal.close(); // Close the loading screen
                    Swal.fire({
                        title: 'Success',
                        text: 'File uploaded successfully.',
                        icon: 'success'
                    });
                    table.ajax.reload();
                })
                .catch(error => {
                    Swal.close(); // Close the loading screen
                    console.error(error);
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while uploading the file.',
                        icon: 'error'
                    });
                });
            }
        });
    });


    

    /* FUNCTIONS */
});