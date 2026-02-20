import dataTablesInitialization from '../../dataTablesInitialization.js';
import { sendToWebView, receiveFromWebView } from '../../WebViewInteraction.js';

$(async function () {

    // --- 1. INITIALIZE ---
    const tableParams = {
        ajax: {
            url: "http://apbiphiqcwb01:1116/api/MachineLotRequests/",
            //url: "https://localhost:7246/api/MachineLotRequests/",
            method: "GET",
            dataSrc: function (json) {
                return json;
            }
        },
        layout: {
            topStart: {},
            topEnd: ['search', 'pageLength'],
        },
        // select: {
        //     style: 'multi',
        //     selector: 'td:first-child'
        // },
        columns: [
            // {
            //     data: null,
            //     defaultContent: '',
            //     orderable: false,
            //     className: 'select-checkbox',
            //     width: "40px"
            // },
            { data: 'releaseNo', visible: true, searchable: true },
            { data: 'partCode', visible: true, searchable: true },
            { data: 'partName', visible: true, searchable: true },
            { data: 'vendorName', visible: true, searchable: true },
            { data: 'dciOtherNo', visible: true, searchable: true },
            { data: 'quantity', visible: true, searchable: true },
            {
                data: 'remarks',
                width: "250px",
                render: function (data) {
                    // Safety: handle null remarks so "null" doesn't print
                    return data ? data : "";
                }
            },
            {
                data: 'yellowCard',
                visible: true,
                searchable: true,
                render: function (data) {
                    return data ? '<i class="fa-solid fa-check text-warning"></i>' : '<i class="fa-solid fa-xmark text-secondary"></i>';
                },
                className: "text-center"
            },
            {
                data: 'createdByFullName',
                visible: true,
                searchable: true,
            },
            {
                data: 'createdDate',
                visible: true,
                searchable: true,
                render: function (data, type, row) {
                    // If the date is null or empty, don't crash, just return blank
                    if (!data) return '';

                    // type === 'display' ensures we only format what the user sees, 
                    // keeping the raw data for sorting and searching
                    if (type === 'display') {
                        const date = new Date(data);
                        // Outputs: "Feb 20, 2026, 02:53 PM" (Adjusted to local browser time)
                        return date.toLocaleString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                    return data;
                }
            },
            {
                data: 'exportedByFullName',
                visible: true,
                searchable: true,
            },
            {
                data: 'exportDate',
                visible: true,
                searchable: true,
                render: function (data, type, row) {
                    if (!data) return '';
                    if (type === 'display') {
                        const date = new Date(data);
                        return date.toLocaleString('en-US', {
                            year: 'numeric', month: 'short', day: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        });
                    }
                    return data;
                }
            },
            {
                data: null, // We don't need a specific data field here
                title: "Actions",
                orderable: false, // Actions shouldn't be sortable
                render: function (data, type, row) {
                    // Baka: relying on row.id inside the HTML is okay, but using the API later is better.
                    return `
                        <button class="btn btn-primary btn-sm w-100 editBtn">Edit</button>
                        <button class="btn btn-danger btn-sm w-100 mt-1 deleteBtn">Delete</button>
                    `;
                }
            }
        ],
        order: [[0, 'desc']]
    };

    let table = dataTablesInitialization("#machine-lot-table", tableParams);

    // --- 2. HELPER FUNCTIONS ---

    // Use this to keep things consistent
    const reloadTable = () => {
        // reload(callback, resetPaging) -> false keeps you on the current page
        table.ajax.reload(null, false);
        console.log("Table data refreshed.");
    };

    // --- 3. WEBVIEW LISTENERS ---

    // When Edit is done
    receiveFromWebView("EditSuccess", async (data) => {
        swal.fire({
            icon: 'success',
            title: 'Updated',
            text: 'Request updated successfully.',
            timer: 1500,
            showConfirmButton: false
        });
        reloadTable();
    });

    // When Delete is done (You were missing this!)
    receiveFromWebView("DeleteSuccess", async (data) => {
        swal.fire({
            icon: 'success',
            title: 'Deleted',
            text: 'Request removed successfully.',
            timer: 1500,
            showConfirmButton: false
        });
        reloadTable();
    });

    // When a NEW item is added (You should probably have this too)
    receiveFromWebView("AddSuccess", async (data) => {
        reloadTable();
    });

    // --- 4. DOM EVENT LISTENERS ---

    // Use .on() delegated to the table ID so it works after pagination changes
    $('#machine-lot-table tbody').on('click', '.editBtn', function () {
        // THE CORRECT WAY: Get the data object for the row
        let rowData = table.row($(this).parents('tr')).data();

        if (!rowData) return; // Safety check

        sendToWebView("openMachineLotRequestEditWinform", {
            releaseNo: rowData.releaseNo,
            modifiedBy: getCookie("CurrentUserEmpNo")
        });
    });

    $('#machine-lot-table tbody').on('click', '.deleteBtn', function () {
        // THE CORRECT WAY
        let rowData = table.row($(this).parents('tr')).data();

        if (!rowData) return;

        swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${rowData.releaseNo}. This cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Notice I'm not waiting for success here. 
                // The "DeleteSuccess" listener above handles the UI update after the WebView finishes.
                sendToWebView("openMachineLotRequestDeleteWinform", {
                    releaseNo: rowData.releaseNo,
                    modifiedBy: getCookie("CurrentUserEmpNo")
                });
            }
        });
    });

    // --- 5. OPTIONAL: POLLING (AUTO-UPDATE) ---
    // Use this if you want the table to update automatically every 30 seconds
    // irrespective of user action.
    
    setInterval(function(){
        //Only reload if the user isn't currently selecting text or interacting
        if(!document.hidden) { 
            reloadTable();
        }
    }, 30000); // 30 seconds
    

    $("#export-button").on("click", function() {
        // Send this data to the WebView for exporting
        sendToWebView("exportMachineLotRequests", { modifiedBy: getCookie("CurrentUserEmpNo") });
    });
});