<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../config/database.php';
include '../../includes/auth.php';
include '../../includes/roles.php';

// Check if the user has the required role
if (!checkRole('machine_operator')) {
    die("Access denied");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $machineId = isset($_POST['machine_id']) ? intval($_POST['machine_id']) : null;
    $producedQuantity = isset($_POST['produced_quantity']) ? intval($_POST['produced_quantity']) : null;
    $rejectedQuantity = isset($_POST['rejected_quantity']) ? intval($_POST['rejected_quantity']) : null;

    if ($productId && $machineId && $producedQuantity !== null && $rejectedQuantity !== null) {
        $operatorId = $_SESSION['user_id'];
        $productionDate = date('Y-m-d H:i:s');

        try {
            $stmt = $pdo->prepare("INSERT INTO production_entries (product_id, machine_id, operator_id, production_date, produced_quantity, rejected_quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$productId, $machineId, $operatorId, $productionDate, $producedQuantity, $rejectedQuantity]);
            echo "Production entry added successfully!";
        } catch (PDOException $e) {
            echo "Failed to add production entry: " . $e->getMessage();
        }
    } else {
        echo "Please fill in all required fields.";
    }
}

// Fetch products
$products = $pdo->query("SELECT id, product_name FROM products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Production Entry</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script>

function fetchMachines(productId) {
    if (!productId) {
        document.getElementById('machine_id').innerHTML = '<option value="">Select Machine</option>';
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_machines.php?product_id=' + encodeURIComponent(productId), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var responseText = xhr.responseText;
                console.log('Raw response:', responseText); // Debugging output
                var machines = JSON.parse(responseText);
                console.log('Machines received:', machines); // Debugging output
                var options = '<option value="">Select Machine</option>';
                for (var i = 0; i < machines.length; i++) {
                    options += '<option value="' + machines[i].id + '">' + machines[i].machine_name + '</option>';
                }
                document.getElementById('machine_id').innerHTML = options;
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response received:', xhr.responseText);
            }
        } else {
            console.error('AJAX request failed with status:', xhr.status);
        }
    };
    xhr.onerror = function() {
        console.error('AJAX request failed');
    };
    xhr.send();
}





        function fetchMachines(productId) {
            if (!productId) {
                document.getElementById('machine_id').innerHTML = '<option value="">Select Machine</option>';
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_machines.php?product_id=' + encodeURIComponent(productId), true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var machines = JSON.parse(xhr.responseText);
                        console.log('Machines received:', machines); // Debugging output
                        var options = '<option value="">Select Machine</option>';
                        for (var i = 0; i < machines.length; i++) {
                            options += '<option value="' + machines[i].id + '">' + machines[i].machine_name + '</option>';
                        }
                        document.getElementById('machine_id').innerHTML = options;
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                    }
                } else {
                    console.error('AJAX request failed with status:', xhr.status);
                }
            };
            xhr.onerror = function() {
                console.error('AJAX request failed');
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h2>Add Production Entry</h2>
            <label for="product_id">Product:</label>
            <select name="product_id" id="product_id" required onchange="fetchMachines(this.value)">
                <option value="">Select Product</option>
                <?php foreach ($products as $product) : ?>
                    <option value="<?php echo htmlspecialchars($product['id']); ?>">
                        <?php echo htmlspecialchars($product['product_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="machine_id">Machine:</label>
            <select name="machine_id" id="machine_id" required>
                <option value="">Select Machine</option>
            </select>
            <label for="produced_quantity">Produced Quantity:</label>
            <input type="number" name="produced_quantity" id="produced_quantity" required>
            <label for="rejected_quantity">Rejected Quantity:</label>
            <input type="number" name="rejected_quantity" id="rejected_quantity" required>
            <input type="submit" value="Add Entry">
        </form>
    </div>
</body>
</html>
