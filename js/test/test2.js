                let btn = document.getElementById('cmdSubmit');

                document.getElementById(""cmdSubmit"").addEventListener('click', function () {
                    approveInspectionData();
                });

                async function approveInspectionData() {
                    try {
                        let data = {
                            checklot: document.getElementById(""txtIqcLotQuery"").value,
                        };

                        await fetch('http://apbiphiqcwb01:1116/API/InspectionDetails/ApproveInspection/', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(data)
                        });

                        // ✅ Only runs after fetch is done
                        sendClosing();

                    } catch (err) {
                        alert(err);
                    }
                }

                function sendClosing() {
                    window.chrome.webview.postMessage(""close"");
                }

                function updateApproval() {
                let isApproved = document.getElementById(""cmdSubmit"").disabled;

                if (isApproved) {
                    
                }
            }
}