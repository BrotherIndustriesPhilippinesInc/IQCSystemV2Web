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
        
        <div class="row mt-4 mb-5"> 
            <div class="col-md-6">
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

        <div class="card shadow-sm mt-4 border-0">
            <div class="card-body">
                <h5 class="card-title mb-3 fw-bold text-secondary">Daily Inspection Summary</h5>
                <div class="table-responsive">
                <table id="summaryTable" class="table table-striped table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                        <th>Date</th>
                        <th>Inspector</th>
                        <th>Supplier</th> 
                        <th>Part Code</th> <th>Category</th>
                        <th>Total Inspections</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
let dataTable = null;

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
        // Added the 5th fetch call for factory-specific trends
        const [summaryRes, approvalRes, memberRes, tableRes, trendRes] = await Promise.all([
            fetch(`http://apbiphiqcwb01:1116/api/Dashboard/inspection-summary?startDate=${startDate}&endDate=${endDate}`),
            fetch(`http://apbiphiqcwb01:1116/api/Dashboard/approval-summary?startDate=${startDate}&endDate=${endDate}`),
            fetch(`http://apbiphiqcwb01:1116/api/Dashboard/members?startDate=${startDate}&endDate=${endDate}`),
            fetch(`http://apbiphiqcwb01:1116/api/Dashboard/summary?startDate=${startDate}&endDate=${endDate}`),
            fetch(`http://apbiphiqcwb01:1116/api/Dashboard/inspection-trends?startDate=${startDate}&endDate=${endDate}`)
        ]);

        if (![summaryRes, approvalRes, memberRes, tableRes, trendRes].every(r => r.ok)) {
            throw new Error("One or more server requests failed.");
        }

        const summaryData = await summaryRes.json();
        const approvalData = await approvalRes.json();
        const membersData = await memberRes.json();
        const tableData = await tableRes.json();
        const trendData = await trendRes.json();

        // 1. Update Cards
        totalEl.textContent = summaryData.totalInspections;
        totalAp.textContent = approvalData.approvedCount;

        // 2. Update Charts & Table
        updateInspectionChart(trendData); // Now uses the dedicated trend data
        updateMemberChart(membersData);
        updateDataTable(tableData.data);

    } catch (e) {
        console.error("Dashboard error:", e);
        alert("Failed to load dashboard data.");
    } finally {
        hideLoading();
    }
}

function updateInspectionChart(trendData) {
    const data = trendData.dailyTrends;

    // 1. Get unique Dates for X-Axis and unique Factories for the colors
    const labels = [...new Set(data.map(d => d.date))].sort();
    const categories = [...new Set(data.map(d => d.category))].sort();

    // 2. Define a strict color palette so lines and bars for the same factory match
    const palette = [
        { line: '#ff6384', bar: 'rgba(255, 99, 132, 0.6)' }, // Red
        { line: '#36a2eb', bar: 'rgba(54, 162, 235, 0.6)' }, // Blue
        { line: '#ffce56', bar: 'rgba(255, 206, 86, 0.6)' }, // Yellow
        { line: '#4bc0c0', bar: 'rgba(75, 192, 192, 0.6)' }, // Teal
        { line: '#9966ff', bar: 'rgba(153, 102, 255, 0.6)' } // Purple
    ];

    let datasets = [];

    // 3. Loop through EACH factory and create TWO datasets (One Line, One Bar)
    categories.forEach((cat, index) => {
        const colors = palette[index % palette.length];

        // Dataset A: Line for Total
        datasets.push({
            type: 'line',
            label: `Total - ${cat}`,
            data: labels.map(date => {
                const match = data.find(d => d.date === date && d.category === cat);
                return match ? match.totalCount : 0;
            }),
            borderColor: colors.line,
            backgroundColor: colors.line,
            tension: 0.3,
            fill: false,
            borderWidth: 2,
            pointRadius: 4
        });

        // Dataset B: Bar for Approved
        datasets.push({
            type: 'bar',
            label: `Approved - ${cat}`,
            data: labels.map(date => {
                const match = data.find(d => d.date === date && d.category === cat);
                return match ? match.approvedCount : 0;
            }),
            backgroundColor: colors.bar,
            borderColor: colors.line,
            borderWidth: 1
        });
    });

    if (inspectionChart) inspectionChart.destroy();

    inspectionChart = new Chart(inspectionCtx, {
        type: 'bar', 
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                x: { 
                    stacked: true // Stacks the factory bars on top of each other!
                }, 
                y: { 
                    beginAtZero: true 
                }
            },
            plugins: {
                legend: {
                    position: 'right', // Move legend to the right so it doesn't crush the chart height
                    labels: { boxWidth: 12 }
                }
            }
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
            labels: labels,
            datasets: [{
                label: 'Inspections',
                data: values,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: { 
            indexAxis: 'y', 
            responsive: true, 
            maintainAspectRatio: false 
        }
    });
}

function updateDataTable(data) {
    if (!dataTable) {
        dataTable = $('#summaryTable').DataTable({
            data: data,
            columns: [
                { data: 'iqcCheckDate' },
                { data: 'checkUser' },
                { data: 'supplierName' },
                { 
                    data: 'partCode', // ADDED THIS OBJECT
                    render: d => `<strong>${d || 'N/A'}</strong>` 
                },
                {
                    data: 'qmLotCategory',
                    render: d => `<span class="badge bg-secondary">${d}</span>`
                },
                { data: 'totalInspections', className: 'text-center fw-bold' }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            destroy: true
        });
    } else {
        dataTable.clear().rows.add(data).draw();
    }
}


// Initial setup logic (Timezone fix)
const today = new Date();
const past7 = new Date();
past7.setDate(today.getDate() - 7);
const start = past7.toLocaleDateString('en-CA');
const end = today.toLocaleDateString('en-CA');

document.getElementById("dateRange").value = `${start} to ${end}`;
fetchAll(start, end);

btn.addEventListener("click", () => {
    const [s, e] = document.getElementById("dateRange").value.split(" to ");
    fetchAll(s, e || s);
});

setInterval(() => {
    const [s, e] = document.getElementById("dateRange").value.split(" to ");
    fetchAll(s, e || s);
}, 60000);
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