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
    <canvas id="inspectionChart" width="800" height="400"></canvas>
    <canvas id="memberChart" width="800" height="400"></canvas>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Initialize Flatpickr
    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "Y-m-d"
    });

    const fetchButton = document.getElementById("testButton");
    const totalEl = document.getElementById("totalInspections");
    const ctx = document.getElementById("inspectionChart").getContext("2d");
    let chart;

    fetchButton.addEventListener("click", async () => {
        const dateRange = document.getElementById("dateRange").value.split(" to ");
        const startDate = dateRange[0];
        const endDate = dateRange[1] || startDate;

        try {
            // Fetch both endpoints in parallel
            const [inspectionRes, approvalRes] = await Promise.all([
                fetch(`https://localhost:7246/api/Dashboard/inspection-summary?startDate=${startDate}&endDate=${endDate}`),
                fetch(`https://localhost:7246/api/Dashboard/approval-summary?startDate=${startDate}&endDate=${endDate}`)
            ]);

            if (!inspectionRes.ok || !approvalRes.ok)
                throw new Error("Network response was not ok");

            const inspectionData = await inspectionRes.json();
            const approvalData = await approvalRes.json();

            // Update total inspections
            totalEl.textContent = inspectionData.totalInspections;

            // Prepare chart data
            const labels = inspectionData.dailyTrends.map(d => new Date(d.date).toLocaleDateString());
            const totalValues = inspectionData.dailyTrends.map(d => d.totalInspections);

            // Match dates with approval data
            const approvedValues = labels.map(labelDate => {
                const found = approvalData.dailyTrends.find(a =>
                    new Date(a.date).toLocaleDateString() === labelDate
                );
                return found ? found.approvedCount : 0;
            });

            // Destroy old chart if exists
            if (chart) chart.destroy();

            // Create combined chart
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Inspections',
                            data: totalValues,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.3
                        },
                        {
                            label: 'Approved Inspections',
                            data: approvedValues,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Date' } },
                        y: { title: { display: true, text: 'Inspections' }, beginAtZero: true }
                    }
                }
            });

        } catch (err) {
            console.error("Failed to fetch dashboard data:", err);
            alert("Failed to fetch data. Check console for details.");
        }
    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const ctx = document.getElementById("memberChart").getContext("2d");
    let memberChart;

    async function fetchMemberData(startDate, endDate) {
        try {
            const res = await fetch(`https://localhost:7246/api/Dashboard/members?startDate=${startDate}&endDate=${endDate}`);
            if (!res.ok) throw new Error("Network response was not ok");

            const membersData = await res.json();

            // Sort descending
            membersData.sort((a, b) => b.numberOfInspection - a.numberOfInspection);

            const labels = membersData.map(m => m.user);
            const values = membersData.map(m => m.numberOfInspection);

            if (memberChart) memberChart.destroy();

            memberChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number of Inspections',
                        data: values,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: { legend: { display: true } },
                    scales: {
                        x: { title: { display: true, text: 'Number of Inspections' }, beginAtZero: true },
                        y: { title: { display: true, text: 'Member' } }
                    }
                }
            });

        } catch (err) {
            console.error("Failed to fetch member data:", err);
        }
    }

    // Trigger both charts on button click
    document.getElementById("testButton").addEventListener("click", () => {
        const dateRange = document.getElementById("dateRange").value.split(" to ");
        const startDate = dateRange[0];
        const endDate = dateRange[1] || startDate;

        fetchMemberData(startDate, endDate);
    });

});
</script>

</body>
</html>
