<?php
global $button;
$primaryButton = $button->primaryButton("testButton", "Fetch Data");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>IQC Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        .chart-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 25px;
        }
    </style>
</head>

<body class="bg-custom container-fluid">

    <?php
    require_once __DIR__ . "/../components/header.php";
    require_once __DIR__ . '/../components/navbar.php';
    ?>

    <div class="container mt-4">
        <h2>Inspection Dashboard</h2>

        <input id="dateRange" type="text" placeholder="Select Date Range" class="form-control mb-3"
            style="max-width: 300px;">
        <button id="testButton" class="btn btn-primary">Reload Dashboard</button>

        <div class="d-flex gap-2 mt-3">
            <div class="card shadow-sm border-0 border-start border-primary border-4 w-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-custom-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-clipboard-data fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-1 text-uppercase fw-bold">Total Inspections</h6>
                        <h2 class="mb-0 fw-bolder text-dark" id="totalInspections">0</h2>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 border-start border-success border-4 w-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-check-circle fs-3 text-success"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-1 text-uppercase fw-bold">Total Approved</h6>
                        <h2 class="mb-0 fw-bolder text-dark" id="totalApproved">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-4 border-0">
            <div class="card-body">
                <h5 class="card-title mb-3 fw-bold text-secondary">Daily Inspection Summary</h5>
                <div class="table-responsive">
                    <table id="summaryTable" class="table table-striped table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Inspector</th>
                                <th>Category</th>
                                <th>Total Inspections</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-4 mb-5"> <div class="col-md-6">
        <div class="chart-container mt-0">
            <h6 class="fw-bold text-secondary text-center mb-3">Trend Analysis</h6>
            <div class="chart-canvas-wrapper">
                <canvas id="inspectionChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-container mt-0">
            <h6 class="fw-bold text-secondary text-center mb-3">Inspector Performance</h6>
            <div class="chart-canvas-wrapper">
                <canvas id="memberChart"></canvas>
            </div>
        </div>
    </div>
</div>
    </div>

    <div id="loadingOverlay"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 9999; color: white; font-size: 24px; font-weight: bold; opacity: 0; transition: opacity 0.2s ease;">
        Loading Data...
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            flatpickr("#dateRange", { mode: "range", dateFormat: "Y-m-d" });

            const loadingOverlay = document.getElementById("loadingOverlay");
            const btn = document.getElementById("testButton");
            const totalEl = document.getElementById("totalInspections");
            const totalAp = document.getElementById("totalApproved");

            const inspectionCtx = document.getElementById("inspectionChart").getContext("2d");
            const memberCtx = document.getElementById("memberChart").getContext("2d");

            let inspectionChart = null;
            let memberChart = null;
            let dataTable = null; // Hold reference to your DataTable

            function showLoading() {
                if (!loadingOverlay) return;
                btn.disabled = true;
                loadingOverlay.style.display = "flex";
                requestAnimationFrame(() => loadingOverlay.style.opacity = 1);
            }

            function hideLoading() {
                if (!loadingOverlay) return;
                btn.disabled = false;
                loadingOverlay.style.opacity = 0;
                setTimeout(() => loadingOverlay.style.display = "none", 200);
            }

            async function fetchAll(startDate, endDate) {
                if (!startDate) return alert("Select a valid date range!");

                showLoading();
                try {
                    // I added the 4th fetch call here to hit your new summary API
                    const [inspectionRes, approvalRes, memberRes, tableRes] = await Promise.all([
                        fetch(`http://apbiphiqcwb01:1116/api/Dashboard/inspection-summary?startDate=${startDate}&endDate=${endDate}`),
                        fetch(`http://apbiphiqcwb01:1116/api/Dashboard/approval-summary?startDate=${startDate}&endDate=${endDate}`),
                        fetch(`http://apbiphiqcwb01:1116/api/Dashboard/members?startDate=${startDate}&endDate=${endDate}`),
                        fetch(`http://apbiphiqcwb01:1116/api/Dashboard/summary?startDate=${startDate}&endDate=${endDate}`)
                    ]);

                    if (!inspectionRes.ok || !approvalRes.ok || !memberRes.ok || !tableRes.ok) {
                        throw new Error("Server returned an error");
                    }

                    const inspectionData = await inspectionRes.json();
                    const approvalData = await approvalRes.json();
                    const membersData = await memberRes.json();
                    const tableData = await tableRes.json();

                    updateInspectionChart(inspectionData, approvalData);
                    updateMemberChart(membersData);

                    // Send the JSON array directly to the new DataTable function
                    updateDataTable(tableData.data);

                    totalEl.textContent = inspectionData.totalInspections;
                    totalAp.textContent = approvalData.approvedCount;

                } catch (e) {
                    console.error("Dashboard error:", e);
                    alert("Failed to load dashboard data.");
                } finally {
                    hideLoading();
                }
            }

            // --- CHART FUNCTIONS (Unchanged, just minimized for brevity) ---
            function updateInspectionChart(inspectionData, approvalData) { /* Your existing logic */ }
            function updateMemberChart(membersData) { /* Your existing logic */ }

            // --- NEW: DATATABLE FUNCTION ---
            function updateDataTable(data) {
                // If the table doesn't exist yet, initialize it
                if (!dataTable) {
                    dataTable = $('#summaryTable').DataTable({
                        data: data,
                        columns: [
                            // These keys MUST exactly match the camelCase JSON you pasted
                            { data: 'iqcCheckDate' },
                            { data: 'checkUser' },
                            {
                                data: 'qmLotCategory',
                                title: 'Factory',
                                render: function (data, type, row) {
                                    // Adding a Bootstrap badge for style
                                    return `<span class="badge bg-secondary">${data}</span>`;
                                }
                            },
                            { data: 'totalInspections', className: 'text-center fw-bold' }
                        ],
                        order: [[0, 'desc']], // Order by Date descending
                        pageLength: 10,
                        destroy: true // Safety mechanism
                    });
                } else {
                    // If the table already exists, clear the old data and inject the new data!
                    dataTable.clear().rows.add(data).draw();
                }
            }

            // FIXING YOUR TIMEZONE BUG. DO NOT REVERT THIS.
            const today = new Date();
            const past7 = new Date();
            past7.setDate(today.getDate() - 7);

            // Using Canadian locale natively outputs YYYY-MM-DD in LOCAL time.
            const start = past7.toLocaleDateString('en-CA');
            const end = today.toLocaleDateString('en-CA');

            document.getElementById("dateRange").value = `${start} to ${end}`;
            fetchAll(start, end);

            // BUTTON RELOAD
            btn.addEventListener("click", () => {
                const [startStr, endStr] = document.getElementById("dateRange").value.split(" to ");
                fetchAll(startStr, endStr || startStr);
            });

            // AUTO RELOAD EVERY 60 SECONDS
            setInterval(() => {
                const [startStr, endStr] = document.getElementById("dateRange").value.split(" to ");
                fetchAll(startStr, endStr || startStr);
            }, 60000);

            function updateInspectionChart(inspectionData, approvalData) {
        // Map the dates for the X-axis
        const labels = inspectionData.dailyTrends.map(d => 
            new Date(d.date).toLocaleDateString('en-CA') // Force local YYYY-MM-DD so they match properly
        );

        // Map the total inspection counts
        const totals = inspectionData.dailyTrends.map(d => d.totalInspections);

        // Map the approved counts, matching by date
        const approved = labels.map(date => {
            const row = approvalData.dailyTrends.find(a =>
                new Date(a.date).toLocaleDateString('en-CA') === date
            );
            // BUG FIX: Changed from row.approvedCount to row.totalApproved to match our C# API!
            return row ? row.approvedCount : 0; 
        });

        // Destroy the old chart instance before drawing a new one to prevent overlay glitches
        if (inspectionChart) inspectionChart.destroy();

        inspectionChart = new Chart(inspectionCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Inspections',
                        data: totals,
                        borderColor: '#4bc0c0',
                        backgroundColor: 'rgba(75,192,192,0.25)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Approved Inspections',
                        data: approved,
                        borderColor: '#C11C84',
                        backgroundColor: 'rgba(193, 28, 132, 0.25)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    function updateMemberChart(membersData) {
        // Sort highest to lowest
        membersData.sort((a, b) => b.numberOfInspection - a.numberOfInspection);

        const labels = membersData.map(m => m.user);
        const values = membersData.map(m => m.numberOfInspection);

        if (memberChart) memberChart.destroy();

        memberChart = new Chart(memberCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Inspections',
                    data: values,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Changed to a nice blue
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: { 
                indexAxis: 'y', // Makes it a horizontal bar chart
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

        });


    </script>
</body>
<style>
    .chart-container {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        /* Give the container a fixed height so the chart knows when to stop growing */
        height: 400px; 
        display: flex;
        flex-direction: column;
    }
    
    /* Add this helper class for the canvas wrapper */
    .chart-canvas-wrapper {
        flex-grow: 1; /* Take up remaining vertical space */
        position: relative; /* Crucial for Chart.js resizing behavior */
        width: 100%;
        height: 100%;
    }
    .bg-custom-primary {
        background-color: #173c7054 !important;
    }
</style>
</html>