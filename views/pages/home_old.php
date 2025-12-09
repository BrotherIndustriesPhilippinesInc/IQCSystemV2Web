<?php
global $button;
$primaryButton = $button->primaryButton("testButton", "Fetch Data");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IQC Dashboard</title>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Optional: Flatpickr theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">
</head>
<body class="bg-custom container-fluid">

<?php 
    require_once __DIR__ . "/../components/header.php";
    require_once __DIR__ . '/../components/navbar.php';
?>

<div class="container mt-4">
    <h2>Inspection Dashboard</h2>

    <!-- Date Range Picker -->
    <input id="dateRange" type="text" placeholder="Select Date Range" class="form-control mb-3" style="max-width: 300px;">

    <!-- Fetch Button -->
    <button id="testButton" class="btn btn-primary">Fetch Data</button>

    <!-- Total Inspections Display -->
    <div class="mt-3">
        <h4>Total Inspections: <span id="totalInspections">0</span></h4>
    </div>

    <!-- Chart Container -->
    <div class="card p-3 mb-4">
        <canvas id="inspectionChart"></canvas>
    </div>

    <div class="card p-3 mb-4">
        <canvas id="memberChart"></canvas>
    </div>

</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "Y-m-d"
    });

    const loadingOverlay = document.getElementById("loadingOverlay");
    const btn = document.getElementById("testButton");
    const totalEl = document.getElementById("totalInspections");

    const inspectionCtx = document.getElementById("inspectionChart").getContext("2d");
    const memberCtx = document.getElementById("memberChart").getContext("2d");

    let inspectionChart = null;
    let memberChart = null;

    function showLoading() { loadingOverlay.style.display = "flex"; }
    function hideLoading() { loadingOverlay.style.display = "none"; }

    async function fetchAll(startDate, endDate) {
        showLoading();
        try {
            const [inspectionRes, approvalRes, memberRes] = await Promise.all([
                fetch(`http://apbiphiqcwb01:1116/api/Dashboard/inspection-summary?startDate=${startDate}&endDate=${endDate}`),
                fetch(`http://apbiphiqcwb01:1116/api/Dashboard/approval-summary?startDate=${startDate}&endDate=${endDate}`),
                fetch(`http://apbiphiqcwb01:1116/api/Dashboard/members?startDate=${startDate}&endDate=${endDate}`)
            ]);

            const inspectionData = await inspectionRes.json();
            const approvalData = await approvalRes.json();
            const membersData = await memberRes.json();

            updateInspectionChart(inspectionData, approvalData);
            updateMemberChart(membersData);

            totalEl.textContent = inspectionData.totalInspections;

        } catch (e) {
            console.error("Dashboard error:", e);
            alert("Failed to fetch dashboard data.");
        } finally {
            hideLoading();
        }
    }

    function updateInspectionChart(inspectionData, approvalData) {
        const labels = inspectionData.dailyTrends.map(d => 
            new Date(d.date).toLocaleDateString()
        );

        const totals = inspectionData.dailyTrends.map(d => d.totalInspections);

        const approved = labels.map(date => {
            const row = approvalData.dailyTrends.find(a =>
                new Date(a.date).toLocaleDateString() === date
            );
            return row ? row.approvedCount : 0;
        });

        if (inspectionChart) inspectionChart.destroy();

        inspectionChart = new Chart(inspectionCtx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Total Inspections',
                        data: totals,
                        borderColor: '#4bc0c0',
                        backgroundColor: 'rgba(75,192,192,0.25)',
                        tension: 0.3
                    },
                    {
                        label: 'Approved Inspections',
                        data: approved,
                        borderColor: '#36a2eb',
                        backgroundColor: 'rgba(54,162,235,0.25)',
                        tension: 0.3
                    }
                ]
            }
        });
    }

    function updateMemberChart(membersData) {
        membersData.sort((a, b) => b.numberOfInspection - a.numberOfInspection);

        const labels = membersData.map(m => m.user);
        const values = membersData.map(m => m.numberOfInspection);

        if (memberChart) memberChart.destroy();

        memberChart = new Chart(memberCtx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Number of Inspections',
                    data: values,
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)'
                }]
            },
            options: { indexAxis: 'y' }
        });
    }

    // LOAD IMMEDIATELY WITH TODAY’S DATE
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("dateRange").value = `${today} to ${today}`;

    fetchAll(today, today);

    // ALLOW BUTTON RELOAD
    btn.addEventListener("click", () => {
        const [start, end] = document.getElementById("dateRange").value.split(" to ");
        fetchAll(start, end || start);
    });

});
</script>


<div id="loadingOverlay" 
     style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.4);
            display:flex;align-items:center;justify-content:center;z-index:9999;
            color:white;font-size:24px;font-weight:bold;display:none;">
    Loading...
</div>

</body>
</html>
