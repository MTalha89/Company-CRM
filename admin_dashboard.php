<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config/database.php';
include 'includes/auth.php';
include 'includes/roles.php';

if (!checkRole('admin')) {
    die("Access denied");
}

// Fetch data for graphs
function fetchData($query) {
    global $pdo;
    return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch production data
$productionDaily = fetchData("SELECT DATE(production_date) AS date, SUM(produced_quantity) AS quantity FROM production_entries GROUP BY DATE(production_date)");
$productionWeekly = fetchData("SELECT YEARWEEK(production_date) AS week, SUM(produced_quantity) AS quantity FROM production_entries GROUP BY YEARWEEK(production_date)");
$productionMonthly = fetchData("SELECT DATE_FORMAT(production_date, '%Y-%m') AS month, SUM(produced_quantity) AS quantity FROM production_entries GROUP BY DATE_FORMAT(production_date, '%Y-%m')");

// Fetch QA data
$qaApproved = fetchData("SELECT DATE(qa_date) AS date, SUM(approved_quantity) AS quantity FROM quality_assurance GROUP BY DATE(qa_date)");
$qaHold = fetchData("SELECT DATE(qa_date) AS date, SUM(hold_quantity) AS quantity FROM quality_assurance GROUP BY DATE(qa_date)");
$qaRejected = fetchData("SELECT DATE(qa_date) AS date, SUM(rejected_quantity) AS quantity FROM quality_assurance GROUP BY DATE(qa_date)");

// Fetch store inventory data
$storeInventory = fetchData("SELECT product_id, SUM(quantity) AS quantity FROM inventory GROUP BY product_id");

// Fetch dispatch data
$dispatchDaily = fetchData("SELECT DATE(dispatch_date) AS date, SUM(quantity) AS quantity FROM dispatch GROUP BY DATE(dispatch_date)");
$dispatchWeekly = fetchData("SELECT YEARWEEK(dispatch_date) AS week, SUM(quantity) AS quantity FROM dispatch GROUP BY YEARWEEK(dispatch_date)");
$dispatchMonthly = fetchData("SELECT DATE_FORMAT(dispatch_date, '%Y-%m') AS month, SUM(quantity) AS quantity FROM dispatch GROUP BY DATE_FORMAT(dispatch_date, '%Y-%m')");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="tabs">
            <button class="tab-link" onclick="openTab('production')">Production</button>
            <button class="tab-link" onclick="openTab('qa')">QA</button>
            <button class="tab-link" onclick="openTab('store')">Store Inventory</button>
            <button class="tab-link" onclick="openTab('dispatch')">Dispatch</button>
        </div>

        <div id="production" class="tab-content">
            <h2>Production Data</h2>
            <canvas id="productionDaily"></canvas>
            <canvas id="productionWeekly"></canvas>
            <canvas id="productionMonthly"></canvas>
        </div>

        <div id="qa" class="tab-content">
            <h2>QA Data</h2>
            <canvas id="qaApproved"></canvas>
            <canvas id="qaHold"></canvas>
            <canvas id="qaRejected"></canvas>
        </div>

        <div id="store" class="tab-content">
            <h2>Store Inventory</h2>
            <canvas id="storeInventory"></canvas>
        </div>

        <div id="dispatch" class="tab-content">
            <h2>Dispatch Data</h2>
            <canvas id="dispatchDaily"></canvas>
            <canvas id="dispatchWeekly"></canvas>
            <canvas id="dispatchMonthly"></canvas>
        </div>
    </div>

    <script>
        function openTab(tabName) {
            var i, x;
            x = document.getElementsByClassName("tab-content");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            document.getElementById(tabName).style.display = "block";
        }

        // Default open tab
        openTab('production');

        // Charts
        var ctxProductionDaily = document.getElementById('productionDaily').getContext('2d');
        var productionDailyChart = new Chart(ctxProductionDaily, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($productionDaily, 'date')); ?>,
                datasets: [{
                    label: 'Daily Production',
                    data: <?php echo json_encode(array_column($productionDaily, 'quantity')); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)'
                }]
            }
        });

        var ctxProductionWeekly = document.getElementById('productionWeekly').getContext('2d');
        var productionWeeklyChart = new Chart(ctxProductionWeekly, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($productionWeekly, 'week')); ?>,
                datasets: [{
                    label: 'Weekly Production',
                    data: <?php echo json_encode(array_column($productionWeekly, 'quantity')); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)'
                }]
            }
        });

        var ctxProductionMonthly = document.getElementById('productionMonthly').getContext('2d');
        var productionMonthlyChart = new Chart(ctxProductionMonthly, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($productionMonthly, 'month')); ?>,
                datasets: [{
                    label: 'Monthly Production',
                    data: <?php echo json_encode(array_column($productionMonthly, 'quantity')); ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.2)'
                }]
            }
        });

        var ctxQaApproved = document.getElementById('qaApproved').getContext('2d');
        var qaApprovedChart = new Chart(ctxQaApproved, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($qaApproved, 'date')); ?>,
                datasets: [{
                    label: 'Approved Products',
                    data: <?php echo json_encode(array_column($qaApproved, 'quantity')); ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)'
                }]
            }
        });

        var ctxQaHold = document.getElementById('qaHold').getContext('2d');
        var qaHoldChart = new Chart(ctxQaHold, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($qaHold, 'date')); ?>,
                datasets: [{
                    label: 'Hold Products',
                    data: <?php echo json_encode(array_column($qaHold, 'quantity')); ?>,
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)'
                }]
            }
        });

        var ctxQaRejected = document.getElementById('qaRejected').getContext('2d');
        var qaRejectedChart = new Chart(ctxQaRejected, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($qaRejected, 'date')); ?>,
                datasets: [{
                    label: 'Rejected Products',
                    data: <?php echo json_encode(array_column($qaRejected, 'quantity')); ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)'
                }]
            }
        });

        var ctxStoreInventory = document.getElementById('storeInventory').getContext('2d');
        var storeInventoryChart = new Chart(ctxStoreInventory, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($storeInventory, 'product_id')); ?>,
                datasets: [{
                    label: 'Store Inventory',
                    data: <?php echo json_encode(array_column($storeInventory, 'quantity')); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)'
                }]
            }
        });

        var ctxDispatchDaily = document.getElementById('dispatchDaily').getContext('2d');
        var dispatchDailyChart = new Chart(ctxDispatchDaily, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($dispatchDaily, 'date')); ?>,
                datasets: [{
                    label: 'Daily Dispatches',
                    data: <?php echo json_encode(array_column($dispatchDaily, 'quantity')); ?>,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 0.2)'
                }]
            }
        });

        var ctxDispatchWeekly = document.getElementById('dispatchWeekly').getContext('2d');
        var dispatchWeeklyChart = new Chart(ctxDispatchWeekly, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($dispatchWeekly, 'week')); ?>,
                datasets: [{
                    label: 'Weekly Dispatches',
                    data: <?php echo json_encode(array_column($dispatchWeekly, 'quantity')); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)'
                }]
            }
        });

        var ctxDispatchMonthly = document.getElementById('dispatchMonthly').getContext('2d');
        var dispatchMonthlyChart = new Chart(ctxDispatchMonthly, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($dispatchMonthly, 'month')); ?>,
                datasets: [{
                    label: 'Monthly Dispatches',
                    data: <?php echo json_encode(array_column($dispatchMonthly, 'quantity')); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)'
                }]
            }
        });
    </script>
</body>
</html>
