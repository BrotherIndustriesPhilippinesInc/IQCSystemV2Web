import { sendToWebView } from "./../../WebViewInteraction.js";

$(async function () {
    //INIT
    let user = JSON.parse(localStorage.getItem("user"));
    //console.table(user['Full_Name']);

    
    let inspectionTable = $('#history-table').DataTable({
    serverSide: true,
    ajax: {
        url: `http://apbiphiqcwb01:1116/api/InspectionDetails/datatable`,
        // url: `https://localhost:7246/api/InspectionDetails/datatable/`,
        type: "POST",
        contentType: "application/json",
        data: function (d) {
            return JSON.stringify(d); // send full DataTables params as JSON
        },
        dataSrc: "data" // expect response to contain { data: [...] }
    },
    columns: [
        { data: 'iqcCheckDate', title: 'Inspection Date' },
        { data: 'checkLot', title: 'Check Lot' },
        { data: 'partCode', title: 'Part Code' },
        { data: 'checkUser', title: 'Inspector' },
        { data: 'supervisor', title: 'Supervisor' },
        { data: 'approver', title: 'Approver' },
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
    ]
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
