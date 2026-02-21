<?php
session_start();
require "../dbconnect.php";   // adjust path if needed

// 🔐 1. Verify Manager Session
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../login.php");
    exit();
}

// ================= ADD VEHICLE =================
if (isset($_POST['add_vehicle'])) {

    $model = trim($_POST['model']);
    $plate = strtoupper(trim($_POST['plate']));
    $type = $_POST['type'];
    $capacity = $_POST['capacity'];
    $odometer = $_POST['odometer'];
    $status = $_POST['status'];

    // 2️⃣ Check unique plate
    $check = $conn->prepare("SELECT vehicle_id FROM vehicles WHERE plate_number = ?");
    $check->execute([$plate]);

    if ($check->rowCount() > 0) {
        $error = "Plate number already exists!";
    } else {

        $stmt = $conn->prepare("INSERT INTO vehicles 
            (model, plate_number, vehicle_type, capacity_kg, odometer_km, status)
            VALUES (?, ?, ?, ?, ?, ?)");

        $stmt->execute([$model, $plate, $type, $capacity, $odometer, $status]);

        $success = "Vehicle added successfully!";
    }
}

// ================= STATUS UPDATE =================
if (isset($_GET['update_status']) && isset($_GET['id'])) {

    $id = $_GET['id'];
    $newStatus = $_GET['update_status'];

    $update = $conn->prepare("UPDATE vehicles SET status = ? WHERE vehicle_id = ?");
    $update->execute([$newStatus, $id]);

    header("Location: manager_vehicle_registry.php");
    exit();
}

// ================= FILTER =================
$filter = $_GET['filter'] ?? 'all';

if ($filter === 'all') {
    $stmt = $conn->query("SELECT * FROM vehicles ORDER BY vehicle_id DESC");
} else {
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE status = ? ORDER BY vehicle_id DESC");
    $stmt->execute([$filter]);
}

$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Vehicle Registry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'], },
                    colors: {
                        brand: { gold: '#D4AF37', dark: '#0A0A0B', light: '#F8F9FA', accent: '#1E1E24' },
                        status: { success: '#22c55e', warning: '#f97316', danger: '#ef4444', info: '#3b82f6', gray: '#6b7280' }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #050505; color: #ffffff; }
        
        /* Glassmorphism Styles */
        .glass-panel {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }
        
        .glass-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Ambient Glows */
        .ambient-glow {
            position: fixed; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.05) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none; z-index: 0;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(212, 175, 55, 0.4); }

        /* Premium Buttons */
        .btn-premium {
            background: linear-gradient(135deg, #D4AF37 0%, #AA8C2C 100%);
            color: #050505; font-weight: 600;
            transition: all 0.3s ease; border: none;
        }
        .btn-premium:hover:not(:disabled) { box-shadow: 0 0 15px rgba(212, 175, 55, 0.4); }
        .btn-premium:disabled { background: #333; color: #777; cursor: not-allowed; box-shadow: none; }
        
        .btn-outline {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white; transition: all 0.3s ease;
        }
        .btn-outline:hover { background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.2); }

        /* Form Inputs */
        .form-input {
            background-color: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: white;
            width: 100%;
            transition: all 0.2s ease;
            outline: none;
        }
        .form-input:focus { border-color: #D4AF37; }
        .form-input.input-error { border-color: #ef4444; }
        .form-input.input-error:focus { box-shadow: 0 0 0 1px #ef4444; }

        select.form-input option {
            background-color: #0A0A0B;
            color: white;
        }

        /* Expandable Form Transition */
        #addVehicleFormContainer {
            transition: max-height 0.4s ease-in-out, opacity 0.4s ease-in-out, padding 0.4s ease-in-out;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            padding-top: 0;
            padding-bottom: 0;
            border-width: 0;
        }
        #addVehicleFormContainer.expanded {
            max-height: 800px;
            opacity: 1;
            padding-top: 2rem;
            padding-bottom: 2rem;
            border-width: 1px;
        }
    </style>
</head>
<body class="h-screen flex overflow-hidden selection:bg-brand-gold selection:text-black relative">

    <!-- Ambient Lighting -->
    <div class="ambient-glow top-[-200px] right-[-100px]"></div>
    <div class="ambient-glow bottom-[-300px] left-[-200px]" style="background: radial-gradient(circle, rgba(10, 150, 255, 0.03) 0%, transparent 70%);"></div>

    <!-- Sidebar Navigation -->
    <?php include 'sidebar.php'; ?>
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full relative z-10 overflow-hidden">
        
        <!-- Top Header Bar -->
        <header class="h-20 glass-panel flex items-center justify-between px-8 border-b border-white/5 shrink-0">
            <h1 class="text-2xl font-semibold">Manager Dashboard</h1>
            
            <div class="flex items-center gap-6">
                <!-- Search -->
                <div class="relative hidden md:block">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Search vehicles, drivers..." class="bg-black/20 border border-white/10 rounded-full py-2 pl-10 pr-4 text-sm text-white focus:outline-none focus:border-brand-gold w-64 transition-colors">
                </div>

                <!-- Profile Actions -->
                <div class="flex items-center gap-4 pl-6 border-l border-white/10">
                    <button class="relative text-gray-400 hover:text-white transition-colors">
                        <i class="ph ph-bell text-2xl"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-brand-gold"></span>
                    </button>
                    <div class="flex items-center gap-3 cursor-pointer group">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-white group-hover:text-brand-gold transition-colors">Alex Mercer</p>
                            <p class="text-xs text-gray-500">Fleet Manager</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                            <i class="ph-fill ph-user text-xl text-gray-300"></i>
                        </div>
                    </div>
                    <a href="logout.php" class="text-gray-500 hover:text-status-danger transition-colors ml-2" title="Logout">
                        <i class="ph ph-sign-out text-2xl"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-8 flex flex-col">
            
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-semibold text-white">Vehicle Registry</h2>
                    <p class="text-sm text-gray-400 mt-1">Manage fleet assets and vehicle availability.</p>
                </div>
                
                <!-- Action Bar -->
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <div class="relative">
                        <i class="ph ph-funnel absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                        <select class="form-input pl-9 py-2 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center] min-w-[160px]">
                            <option value="all">Filter: All Status</option>
                            <option value="available">Available</option>
                            <option value="on_trip">On Trip</option>
                            <option value="in_maintenance">In Maintenance</option>
                            <option value="retired">Retired</option>
                        </select>
                    </div>
                    <div class="relative flex-1 md:w-64">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                        <input type="text" placeholder="Search plate or model..." class="form-input pl-9 py-2">
                    </div>
                    <a href="add_vehicle.php"
   class="btn-premium px-5 py-2.5 rounded-xl text-sm font-bold">
   Add New Vehicle
                </a>
                </div>
            </div>

            <!-- Vehicle List Table Area -->
            <div class="glass-panel rounded-3xl overflow-hidden flex-1 flex flex-col min-h-[400px]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white/5 text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-white/10">
                                <th class="py-4 pl-6">Vehicle ID</th>
                                <th class="py-4 px-4">Model</th>
                                <th class="py-4 px-4">Plate Number</th>
                                <th class="py-4 px-4">Type</th>
                                <th class="py-4 px-4 text-right">Capacity (kg)</th>
                                <th class="py-4 px-4 text-right">Odometer (km)</th>
                                <th class="py-4 px-4 text-center">Status</th>
                                <th class="py-4 pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white/5">
<?php foreach ($vehicles as $v): ?>
<tr class="hover:bg-white/5 transition-colors group">

    <td class="py-4 pl-6 font-medium text-white">
        #VH-<?= $v['vehicle_id']; ?>
    </td>

    <td class="py-4 px-4 text-gray-300">
        <?= htmlspecialchars($v['model']); ?>
    </td>

    <td class="py-4 px-4 font-mono text-gray-200">
        <?= htmlspecialchars($v['license_plate']); ?>
    </td>

    <td class="py-4 px-4 text-gray-300">
        <?= htmlspecialchars($v['type']); ?>
    </td>

    <td class="py-4 px-4 text-right text-gray-300">
        <?= number_format($v['max_capacity'], 2); ?>
    </td>

    <td class="py-4 px-4 text-right text-gray-300">
        <?= number_format($v['odometer']); ?>
    </td>

    <td class="py-4 px-4 text-center">
        <?= ucfirst(str_replace('_',' ', $v['status'])); ?>
    </td>

    <td class="py-4 pr-6 text-right">
        <a href="?update_status=available&id=<?= $v['vehicle_id']; ?>" class="text-green-400">✔</a>
        <a href="?update_status=in_shop&id=<?= $v['vehicle_id']; ?>" class="text-yellow-400 ml-2">🛠</a>
        <a href="?update_status=retired&id=<?= $v['vehicle_id']; ?>" class="text-gray-400 ml-2">📦</a>
    </td>

</tr>
<?php endforeach; ?>
</tbody>                    </table>
                </div>
                
                <!-- Pagination Footer -->
                <div class="mt-auto px-6 py-4 border-t border-white/5 flex items-center justify-between">
                    <p class="text-xs text-gray-500">Showing 1 to 4 of 142 vehicles</p>
                    <div class="flex items-center gap-2">
                        <button class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors"><i class="ph ph-caret-left"></i></button>
                        <button class="w-8 h-8 rounded-lg bg-brand-gold/20 flex items-center justify-center text-brand-gold font-medium text-sm">1</button>
                        <button class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors text-sm">2</button>
                        <button class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors text-sm">3</button>
                        <button class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors"><i class="ph ph-caret-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- Custom Footer -->
            <footer class="mt-auto pt-6 border-t border-white/5 pb-4 shrink-0">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-[11px] text-gray-500 tracking-wide">
                    <p class="font-medium text-gray-400">
                        Created By: 
                        <span class="text-brand-gold">Divy Choksi</span> – Frontend <span class="mx-1">|</span> 
                        <span class="text-brand-gold">Dhruv Pandya</span> – Backend <span class="mx-1">|</span> 
                        <span class="text-brand-gold">Jay Gajjar</span> – Documentation <span class="mx-1">|</span> 
                        <span class="text-brand-gold">Naivedi Binjwa</span> – Validation
                    </p>
                    <p>&copy; 2026 OptiFleet | All Rights Reserved</p>
                </div>
            </footer>
        </main>
    </div>

    <!-- Frontend Form Validation & Interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // Toggle Add Vehicle Form
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const closeFormBtn = document.getElementById('closeFormBtn');
            const formContainer = document.getElementById('addVehicleFormContainer');
            
            toggleFormBtn.addEventListener('click', () => {
                formContainer.classList.add('expanded');
                document.getElementById('vModel').focus();
            });
            
            closeFormBtn.addEventListener('click', () => {
                formContainer.classList.remove('expanded');
            });

            // Form Validation Logic
            const form = document.getElementById('vehicleForm');
            const btnSubmit = document.getElementById('submitVehicleBtn');
            
            const inputs = {
                model: document.getElementById('vModel'),
                plate: document.getElementById('vPlate'),
                type: document.getElementById('vType'),
                capacity: document.getElementById('vCapacity'),
                odo: document.getElementById('vOdo'),
                status: document.getElementById('vStatus')
            };

            const validators = {
                model: (val) => val.trim().length > 1,
                plate: (val) => /^[A-Z0-9-]{4,10}$/i.test(val.trim()), // Basic plate format (alphanumeric & dashes, 4-10 chars)
                type: (val) => val !== "",
                capacity: (val) => !isNaN(val) && parseFloat(val) > 0,
                odo: (val) => !isNaN(val) && parseFloat(val) >= 0,
                status: (val) => val !== ""
            };

            function validateField(inputElement, fieldKey) {
                const isValid = validators[fieldKey](inputElement.value);
                const errorMsg = inputElement.closest('.space-y-2').querySelector('.error-msg');
                
                if (!isValid && inputElement.value !== "") {
                    inputElement.classList.add('input-error');
                    errorMsg.classList.remove('hidden');
                } else {
                    inputElement.classList.remove('input-error');
                    errorMsg.classList.add('hidden');
                }
                
                return isValid;
            }

            function checkFormValidity() {
                let isFormValid = true;
                for (const key in inputs) {
                    if (!validators[key](inputs[key].value)) {
                        isFormValid = false;
                        break;
                    }
                }
                btnSubmit.disabled = !isFormValid;
            }

            for (const key in inputs) {
                inputs[key].addEventListener('input', (e) => {
                    if(key === 'plate') {
                        e.target.value = e.target.value.toUpperCase(); // Force uppercase for plate
                    }
                    validateField(e.target, key);
                    checkFormValidity();
                });
                
                inputs[key].addEventListener('blur', (e) => {
                    validateField(e.target, key);
                });
            }

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if(!btnSubmit.disabled) {
                    btnSubmit.innerHTML = `<i class="ph ph-spinner animate-spin text-lg"></i> Processing...`;
                    
                    // Mock Success
                    setTimeout(() => {
                        alert("Success: Vehicle added to registry successfully.");
                        form.reset();
                        checkFormValidity();
                        formContainer.classList.remove('expanded');
                        btnSubmit.innerHTML = `<i class="ph-bold ph-plus"></i> Add Vehicle`;
                    }, 1200);
                }
            });
        });
    </script>
</body>
</html>
