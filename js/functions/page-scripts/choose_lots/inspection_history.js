import { sendToWebView } from "./../../WebViewInteraction.js";

$(async function () {
    //INIT
    let user = JSON.parse(localStorage.getItem("user"));
    //console.table(user['Full_Name']);

    
    let inspectionTable = $('#history-table').DataTable({
    serverSide: true,
    
    ajax: {
        url: `http://apbiphiqcwb01:1116/api/InspectionDetails/datatable`,
        //url: `https://localhost:7246/api/InspectionDetails/datatable/`,
        type: "POST",
        contentType: "application/json",
        data: function (d) {
            return JSON.stringify(d);
        },
        dataSrc: "data"
    },
    columns: [
        { data: 'stockInCollectDate', title: 'Stock-In Date' },
        { data: 'iqcCheckDate', title: 'Inspection Date' },
        { data: 'checkLot', title: 'Check Lot' },
        { data: 'partCode', title: 'Part Code' },

        // 💥 Single merged Vendor/Supplier column
        {
            data: 'vendorSupplierMerged',
            title: 'Vendor / Supplier',
            render: function (data, type, row) {
                if (!data) return '';
                // Replace newlines with <br> for multi-line display
                return data.replace(/\n/g, '<br>');
            }
        },

        { data: 'lotNo', title: 'Lot No' },
        { data: 'checkUser', title: 'Inspector' },
        { data: 'supervisor', title: 'Supervisor' },
        { data: 'approver', title: 'Approver' },
        { data: 'remarks', title: 'Remarks' },

        {
            data: null,
            orderable: false,
            className: "text-center",
            render: function (data, type, row) {
                return `
                    <button class="btn btn-success btn-sm check-btn" data-checklot="${row.checkLot}">
                        Check
                    </button>
                `;
            }
        }
    ],
    layout: {
        topStart: {
            buttons: ['colvis','copy', 'csv', 'excel', 'pdf', 'print'],
        },
        topEnd: ['search', 'pageLength'],
    },
    lengthMenu: [10, 25, 50, 100, 200, 500, 1000],

});




setInterval(function () {
    inspectionTable.ajax.reload(null, false); // false = keep paging
}, 5000); // 5 sec interval

//EVENTS
$(document).on('click', '.check-btn', function () {
    sendToWebView("openHistoryWinform",{ checkLot: $(this).data("checklot") } );
});

    //FUNCTIONS
});
