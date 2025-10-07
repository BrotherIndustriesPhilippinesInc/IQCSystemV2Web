import dataTablesInitialization from "../../dataTablesInitialization.js";
import apiCall  from "../../apiCall.js";

$(function(){

    const tableParams = {
        ajax:{
            url: "http://apbiphiqcwb01:1116/api/SystemApproverLists", // your endpoint
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
        columns: [
            { data: 'employeeNumber', visible: true, searchable: true  },
            { data: 'fullName', visible: true, searchable: true  },
            { data: 'adid', visible: true, searchable: true  },
            { data: 'mesName', visible: true, searchable: true  },
            { data: 'emailAddress', visible: true, searchable: true  },
            { data: 'section', visible: true, searchable: true  },
            { data: 'position', visible: true, searchable: true  },
            {
                data: null,
                title: "Actions",
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-primary" data-user_id="${row.id}" data-bs-toggle="modal" data-bs-target="#userEditModal">Edit</button>
                        <button class="deleteUser btn text-primary" data-user_id="${row.employeeNumber}">Delete</button>
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
        }
    }
    let table = dataTablesInitialization("#inspection-table", tableParams);


    /* EVENTS */
    $('#addUserBtn').on('click', function(){
        $('#userAddModal input').val('');
    });

    $('#saveUser').on('click', async function(){
        const userData = {
            employeeNumber: $('#employeeNumber').val(),
            mesName: $('#emesName').val(),
        };
        
        await apiCall('http://apbiphiqcwb01:1116/api/SystemApproverLists', 'POST', userData).then((response) => {
            if(response && response.id){
                Swal.fire({
                    icon: 'success',
                    title: 'User added successfully',
                    timerProgressBar: true,
                    timer: 1000
                });

                table.ajax.reload(null, false); // Reload table data without resetting pagination

                const userAddModal = bootstrap.Modal.getInstance(document.getElementById('userAddModal'));
                if (userAddModal) {
                    userAddModal.hide();
                }
            }
        });

    });

    $(document).on('click', '.deleteUser', async function(){
        const userId = $(this).data('user_id');
        const userRow = $(this).closest('tr');
        const userName = userRow.find('td').eq(1).text(); // Assuming fullName is the second column

        const result = await Swal.fire({
            title: `Are you sure you want to delete ${userName}?`,
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });
        if (result.isConfirmed) {
            await apiCall(
                "http://apbiphiqcwb01:1116/api/SystemApproverLists/delete-approver",
                "POST",
                { employeeNumber: userId }
            ).then((response) => {
                if (response.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'User deleted successfully',
                        timerProgressBar: true,
                        timer: 1000
                    });
                    table.ajax.reload(null, false);
                }
            });

        }
    });

    /* FUNCTIONS */
});