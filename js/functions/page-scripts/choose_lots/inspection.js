/* import { ajaxFetch } from "../../ajaxFetch.js";
import dataTablesInitialization from "../../dataTablesInitialization.js"; */
import { sendToWebView } from "./../../WebViewInteraction.js";

// let data = await ajaxFetch("../helpers/APIFetch.php");
// data = data["data"]["samplingOrders"];

// const tableParams = {
//     select: {
//         style: 'multi',
//         selector: 'td:first-child'
//     },
//     layout: {
//         topStart: {
//             buttons: ['colvis']
//         },
//         topEnd:['search','pageLength'],
//     },
//     data: data,
//     columns: [
//         { 
//             data: null, 
//             orderable: false, 
//             className: 'select-checkbox', 
//             defaultContent: '' 
//         },
//         { data: 'factoryCode', visible: true, searchable: true  },
//         { data: 'stockInDate', visible: true, searchable: true  },
//         { data: 'Status', visible: true, searchable: true  },
//         { data: 'checkLot', visible: true, searchable: true  },
//         { data: 'category', visible: true, searchable: true  },
//         { data: 'partCode', visible: true, searchable: true  },
//         { data: 'vendorCode', visible: true, searchable: true  },
//         { data: 'lotInQty', visible: true, searchable: true  },
//         { data: 'aoqlStandard', visible: true, searchable: true  },
//         { data: 'samplingLevel', visible: true, searchable: true  },
//         { data: 'samplesQty', visible: true, searchable: true  },
//         { data: 'productionLotNo', visible: false, searchable: true  },
//         { data: 'lotJudge', visible: false, searchable: true  },
//         { data: 'model', visible: false, searchable: true  },
//         { data: 'issueNo', visible: false, searchable: true  },
//         { data: 'outgoingInspect', visible: false, searchable: true  },
//         { data: 'DataConfirm3C', visible: false, searchable: true  },
//         { data: 'printStatus', visible: true, searchable: true  },
//         { data: 'printStatus', visible: false, searchable: true  },
//         { data: 'user', visible: false, searchable: true  },
//         { data: 'inspection_timestamp', visible: false, searchable: true  }
//     ],
//     createdRow: function(row, data, dataIndex) {
//         // Add a data attribute to the row for later use
//         $(row).addClass('inspectionItemRow');
//         $(row).attr('data-checkLot', data["checkLot"]);
//         $(row).attr('data-partCode', data["partCode"]);
//         $(row).attr('data-vendorCode', data["vendorCode"]);
//     }
// }
// dataTablesInitialization("#inspection-table", tableParams);

// $("#inspection-table").on("dblclick", "tbody tr", function() {
//     const checkLot = $(this).attr("data-checkLot");
//     const partCode = $(this).attr("data-partCode");
//     const vendorCode = $(this).attr("data-vendorCode");

//     window.location.href = `/iqcv2/choose_lots/inspection_details?checkLot=${checkLot}&partCode=${partCode}&vendorCode=${vendorCode}`;
// });

//Open Window form

$(function(){
    openWinform();
});
function openWinform(){
    sendToWebView("openInspectionWinform",{});
}