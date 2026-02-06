
import dataTablesInitialization from '../../dataTablesInitialization.js';
import { sendToWebView, receiveFromWebView } from '../../WebViewInteraction.js';

$(async function(){
    // INITIALIZE
    const tableParams = {
        ajax:{
            url: "http://apbiphiqcwb01:1116/api/MachineLotRequests/", // your endpoint
            method: "GET",
            dataSrc: function (json) {
                return json; // extract the data array from your JSON
            }
        },
        layout: {
            topStart: {
                //buttons: ['colvis']
            },
            topEnd:['search','pageLength'],
        },
        select: {
            style: 'multi', // Use 'os' for single/ctrl+click, 'multi' for checkboxes
            selector: 'td:first-child'
        },
        columns: [
            //CHECK BOX
            {
                // THE SELECT COLUMN
                data: null,
                defaultContent: '',
                orderable: false,
                className: 'select-checkbox', // This class tells DataTables to draw the box
                width: "40px"
            },
            { data: 'releaseNo', visible: true, searchable: true },
            { data: 'partCode', visible: true, searchable: true  },
            { data: 'partName', visible: true, searchable: true  },
            { data: 'vendorName', visible: true, searchable: true  },
            { data: 'dciOtherNo', visible: true, searchable: true  },
            { data: 'quantity', visible: true, searchable: true  },
            // { data: 'releaseNo', visible: true, searchable: true  },
            {
                data: 'remarks',
                width: "250px", // Limit this!
                
            },
            {

                data: 'yellowCard', 
                visible: true, 
                searchable: true, 
                render: function (data, type, row) {
                    return data ? '<i class="fa-solid fa-check"></i>' : '<i class="fa-solid fa-xmark"></i>';
                },
                className: "text-center"
            },
            
            {
                data: null,
                title: "Actions",
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-primary editBtn" data-user_id="${row.id}" data-bs-toggle="modal" data-bs-target="#userUpdateModal">Edit</button>
                        <button class="deleteBtn btn text-primary" data-user_id="${row.employeeNumber}">Delete</button>
                    `;
                }
            }

        ],
        createdRow: function(row, data, dataIndex) {
            // Add a data attribute to the row for later use
            // $(row).addClass('inspectionItemRow');
            // $(row).attr('data-checkLot', data["checkLot"]);
            // $(row).attr('data-partCode', data["partCode"]);
            // $(row).attr('data-vendorCode', data["vendorCode"]);
        },
        order: [[1, 'desc']]
    }
    let table = dataTablesInitialization("#machine-lot-table", tableParams);
    
    // EVENT LISTENERS
    receiveFromWebView("EditSuccess", async (data) => {
        swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Machine Lot Request has been updated successfully.'
        }).then(() => {
            table.ajax.reload(null, false); // Reload the table data without resetting pagination
            
        })
    });
    $(document).on('click', '#machine-lot-table .editBtn', editData);

    $(document).on('click', '#machine-lot-table .deleteBtn', deleteData);

    function editData(){
        let releaseNo = $(this).closest('tr').find('td:eq(1)').text();

        //alert(releaseNo);

        sendToWebView("openMachineLotRequestEditWinform", { releaseNo: releaseNo, modifiedBy: getCookie("CurrentUserEmpNo") });
    }

    function deleteData() {
        let releaseNo = $(this).closest('tr').find('td:eq(1)').text();

        //alert(releaseNo);

        swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                sendToWebView("openMachineLotRequestDeleteWinform", { releaseNo: releaseNo, modifiedBy: getCookie("CurrentUserEmpNo") });
            }
        });

    }
});