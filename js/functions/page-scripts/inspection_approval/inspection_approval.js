import { sendToWebView } from "./../../WebViewInteraction.js";

$(async function () {
    //INIT
    let user = JSON.parse(localStorage.getItem("user"));
    //console.table(user['Full_Name']);

    
    let inspectionTable = $('#approval-table').DataTable({
        ajax: {
            url: `http://apbiphiqcwb01:1116/api/InspectionDetails/supervisor/${user['Full_Name']}`,
            dataSrc: ''
        },
        columns: [
            { data: 'iqcCheckDate' },
            { data: 'checkLot' },
            { data: 'partCode' },
            { data: 'checkUser' },
            { data: 'supervisor' },
            //{ data: 'isApproved' },
            {
                data: null,
                orderable: false,

                render: function (data, type, row) {
                    return `
                        <button class="btn btn-success btn-sm check-btn" data-checklot="${row.checkLot}">Check</button>
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
        sendToWebView("openApprovalWinform",{ checkLot: $(this).data("checklot") } );
    });

    //FUNCTIONS
});
