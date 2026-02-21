<?php
session_start();
require "../dbconnect.php";

// 🔐 Manager check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../login.php");
    exit();
}

$error = "";

// 🚛 ADD VEHICLE
if (isset($_POST['add_vehicle'])) {

    $model = trim($_POST['model']);
    $license_plate = strtoupper(trim($_POST['license_plate']));
    $type = $_POST['type'];
    $max_capacity = $_POST['max_capacity'];
    $acquisition_cost = $_POST['acquisition_cost'];
    $odometer = $_POST['odometer'];
    $status = $_POST['status'];

    // Check unique license plate
    $check = $conn->prepare("SELECT vehicle_id FROM vehicles WHERE license_plate = ?");
    $check->execute([$license_plate]);

    if ($check->rowCount() > 0) {
        $error = "License plate already exists!";
    } else {

        $stmt = $conn->prepare("INSERT INTO vehicles 
            (license_plate, model, type, max_capacity, acquisition_cost, odometer, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $license_plate,
            $model,
            $type,
            $max_capacity,
            $acquisition_cost,
            $odometer,
            $status
        ]);

        // ✅ Redirect back after success
        header("Location: manager_vehicle_registry.php?success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Vehicle</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-black text-white p-10">

<h2 class="text-2xl mb-6">Add New Vehicle</h2>

<?php if($error): ?>
    <div class="bg-red-500/20 text-red-400 p-3 mb-4 rounded">
        <?= $error ?>
    </div>
<?php endif; ?>

<form method="POST" class="space-y-4 max-w-xl">

    <input type="text" name="model" placeholder="Vehicle Model" class="w-full p-2 bg-gray-900 border border-gray-700 rounded" required>

    <input type="text" name="license_plate" placeholder="License Plate" class="w-full p-2 bg-gray-900 border border-gray-700 rounded uppercase" required>

    <select name="type" class="w-full p-2 bg-gray-900 border border-gray-700 rounded" required>
        <option value="">Select Type</option>
        <option value="Truck">Truck</option>
        <option value="Van">Van</option>
        <option value="Bike">Bike</option>
    </select>

    <input type="number" step="0.01" name="max_capacity" placeholder="Max Capacity (kg)" class="w-full p-2 bg-gray-900 border border-gray-700 rounded" required>

    <input type="number" step="0.01" name="acquisition_cost" placeholder="Acquisition Cost" class="w-full p-2 bg-gray-900 border border-gray-700 rounded" required>

    <input type="number" name="odometer" placeholder="Odometer (km)" class="w-full p-2 bg-gray-900 border border-gray-700 rounded" required>

    <select name="status" class="w-full p-2 bg-gray-900 border border-gray-700 rounded" required>
        <option value="available">Available</option>
        <option value="on_trip">On Trip</option>
        <option value="in_shop">In Maintenance</option>
        <option value="retired">Retired</option>
    </select>

    <button type="submit" name="add_vehicle"
        class="bg-yellow-500 text-black px-6 py-2 rounded font-bold">
        Add Vehicle
    </button>

</form>

</body>
</html>