

$(async function () {
    //INIT
    let inspectionTable = $('#approval-table').DataTable({
        ajax: {
            url: 'http://apbiphiqcwb01:1116/api/InspectionDetails',
            dataSrc: ''
        },
        columns: [
            { data: 'iqcCheckDate' },
            { data: 'checkLot' },
            { data: 'partCode' },
            { data: 'checkUser' },
            { data: 'isApproved' },
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

    //EVENTS
    $(document).on('click', '.check-btn', function () {
        window.open(
            `http://ZZPDE31G:ZZPDE31G@10.248.1.10/BIPHMES/BenQGuru.eMES.Web.IQC/FIQCCheckResultMpNew.aspx?IQCLOT=${this.dataset.checklot}`,
            "popupWindow",
            "width=1280,height=720,scrollbars=yes"
        );
    });


    //FUNCTIONS
});
