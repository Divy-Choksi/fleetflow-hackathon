<?php
session_start();
require "dbconnect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// ==========================================
// Total Vehicles
$stmt = $conn->query("SELECT COUNT(*) FROM vehicles");
$totalVehicles = $stmt->fetchColumn();

// Available Vehicles
$stmt = $conn->query("SELECT COUNT(*) FROM vehicles WHERE status = 'available'");
$availableVehicles = $stmt->fetchColumn();

// On Trip Vehicles
$stmt = $conn->query("SELECT COUNT(*) FROM vehicles WHERE status = 'on_trip'");
$onTripVehicles = $stmt->fetchColumn();

// In Maintenance
$stmt = $conn->query("SELECT COUNT(*) FROM vehicles WHERE status = 'in_shop'");
$inMaintenance = $stmt->fetchColumn();

// Utilization Rate
$utilizationRate = ($totalVehicles > 0) 
    ? round(($onTripVehicles / $totalVehicles) * 100, 1) 
    : 0;
    $stmt = $conn->query("
    SELECT v.license_plate, m.issue_description
    FROM maintenance_logs m
    JOIN vehicles v ON m.vehicle_id = v.vehicle_id
    WHERE m.status = 'pending'
    LIMIT 3
");

$maintenanceAlerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// licens
$stmt = $conn->query("
    SELECT full_name, license_expiry
    FROM drivers
    WHERE license_expiry <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
");

$licenseAlerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

//graph
$tripData = [];

$stmt = $conn->query("
    SELECT DATE(completed_at) as trip_date, COUNT(*) as total
    FROM trips
    WHERE status = 'completed'
    AND completed_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(completed_at)
");

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize last 7 days with 0
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $tripData[$date] = 0;
}

// Fill actual data
foreach ($results as $row) {
    $tripData[$row['trip_date']] = $row['total'];
}

?>
<?php
$stmt = $conn->query("
    SELECT t.trip_id, v.license_plate, d.full_name, 
           t.cargo_weight, t.origin, t.destination, t.status
    FROM trips t
    JOIN vehicles v ON t.vehicle_id = v.vehicle_id
    JOIN drivers d ON t.driver_id = d.driver_id
    WHERE t.status IN ('dispatched')
    ORDER BY t.created_at DESC
    LIMIT 5
");

$activeTrips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Command Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Chart.js for Data Visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'], },
                    colors: {
                        brand: { gold: '#D4AF37', dark: '#0A0A0B', light: '#F8F9FA', accent: '#1E1E24' },
                        status: { success: '#22c55e', warning: '#f97316', danger: '#ef4444', info: '#3b82f6' }
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
        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px rgba(212, 175, 55, 0.15);
            border-color: rgba(212, 175, 55, 0.2);
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
        .btn-premium:hover { box-shadow: 0 0 15px rgba(212, 175, 55, 0.4); }
        
        .btn-outline {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white; transition: all 0.3s ease;
        }
        .btn-outline:hover { background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="h-screen flex overflow-hidden selection:bg-brand-gold selection:text-black relative">

    <!-- Ambient Lighting -->
    <div class="ambient-glow top-[-200px] right-[-100px]"></div>
    <div class="ambient-glow bottom-[-300px] left-[-200px]" style="background: radial-gradient(circle, rgba(10, 150, 255, 0.03) 0%, transparent 70%);"></div>

    <!-- Sidebar Navigation -->
    <aside class="w-64 glass-panel flex flex-col z-20 shrink-0 border-r border-white/5">
        <div class="h-20 flex items-center px-6 border-b border-white/5 shrink-0">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-gold to-yellow-600 flex items-center justify-center mr-3">
                <i class="ph ph-steering-wheel text-black text-xl font-bold"></i>
            </div>
            <span class="text-xl font-semibold tracking-wide text-white">
                Opti<span class="text-brand-gold">Fleet</span>
            </span>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <!-- Active State -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand-gold/10 text-brand-gold border border-brand-gold/20 transition-all">
                <i class="ph-fill ph-squares-four text-xl"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-truck text-xl"></i>
                <span class="font-medium">Vehicle Registry</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-users text-xl"></i>
                <span class="font-medium">Drivers</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-map-pin-line text-xl"></i>
                <span class="font-medium">Trip Dispatcher</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all flex-wrap relative">
                <i class="ph ph-wrench text-xl"></i>
                <span class="font-medium">Maintenance</span>
                <!-- Notification Badge -->
                <span class="absolute right-4 w-2 h-2 rounded-full bg-status-danger"></span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-chart-line-up text-xl"></i>
                <span class="font-medium">Reports</span>
            </a>
        </nav>
        
        <div class="p-4 border-t border-white/5">
            <div class="glass-card rounded-xl p-4 text-center">
                <i class="ph-fill ph-headset text-2xl text-brand-gold mb-2"></i>
                <p class="text-xs text-gray-400 mb-3">Need Help?</p>
                <button class="w-full py-2 text-xs font-medium rounded-lg bg-white/5 hover:bg-white/10 text-white transition-colors">Support Center</button>
            </div>
        </div>
    </aside>

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
                    <!-- DHRUV: Logout logic link here -->
                    <a href="logout.php" class="text-gray-500 hover:text-status-danger transition-colors ml-2" title="Logout">
                        <i class="ph ph-sign-out text-2xl"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-8 space-y-8">
            
            <!-- Welcome & Quick Actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-xl text-gray-300 font-light">Welcome back, <span class="text-white font-medium">Alex</span></h2>
                    <p class="text-sm text-gray-500 mt-1">Here is what's happening with your fleet today.</p>
                </div>
                <div class="flex gap-3">
                    <button class="btn-outline px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i class="ph ph-plus"></i> Add Vehicle
                    </button>
                    <!-- Core Action Button -->
                    <button class="btn-premium px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
                        <i class="ph-fill ph-paper-plane-tilt"></i> Dispatch Trip
                    </button>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <!-- DHRUV: Inject PHP variables into these cards ($totalVehicles, etc.) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                
                <div class="glass-card rounded-2xl p-5 relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                            <i class="ph ph-truck text-xl text-gray-300"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-1"><?= $totalVehicles ?></h3>
                        <p class="text-sm text-gray-400 font-medium">Total Vehicles</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-status-success/5 rounded-bl-full -mr-4 -mt-4 transition-all group-hover:bg-status-success/10"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-status-success/10 flex items-center justify-center border border-status-success/20">
                            <i class="ph ph-check-circle text-xl text-status-success"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-1"><?= $availableVehicles ?></h3>
                        <p class="text-sm text-gray-400 font-medium">Available (Idle)</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-brand-gold/5 rounded-bl-full -mr-4 -mt-4 transition-all group-hover:bg-brand-gold/10"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-brand-gold/10 flex items-center justify-center border border-brand-gold/20">
                            <i class="ph ph-path text-xl text-brand-gold"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-1"><?= $onTripVehicles ?></h3>
                        <p class="text-sm text-gray-400 font-medium">Currently On Trip</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-status-danger/5 rounded-bl-full -mr-4 -mt-4 transition-all group-hover:bg-status-danger/10"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-status-danger/10 flex items-center justify-center border border-status-danger/20">
                            <i class="ph ph-wrench text-xl text-status-danger"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-1"><?= $inMaintenance ?></h3>
                        <p class="text-sm text-gray-400 font-medium">In Maintenance</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-5 relative overflow-hidden">
                    <div class="flex justify-between items-start mb-2">
                        <div class="w-10 h-10 rounded-xl bg-status-info/10 flex items-center justify-center border border-status-info/20">
                            <i class="ph ph-chart-pie-slice text-xl text-status-info"></i>
                        </div>
                        <span class="text-xs font-semibold text-status-success bg-status-success/10 px-2 py-1 rounded-md">+2.4%</span>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-1"><?= $utilizationRate ?>%</h3>
                        <p class="text-sm text-gray-400 font-medium mb-3">Fleet Utilization</p>
                        <!-- Progress Bar -->
                        <div class="w-full bg-white/10 rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-status-info to-blue-400 h-1.5 rounded-full" style="width: <?= $utilizationRate ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Grid (Tables & Panels) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT COLUMN: Active Trips Table & Chart -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Active Trips Data Table -->
                    <div class="glass-panel rounded-3xl p-6 relative">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-white">Active Dispatch Operations</h3>
                            <button class="text-sm text-brand-gold hover:text-white transition-colors font-medium">View All Trips &rarr;</button>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-white/10">
                                        <th class="pb-4 pl-2">Trip ID</th>
                                        <th class="pb-4">Vehicle</th>
                                        <th class="pb-4">Driver</th>
                                        <th class="pb-4">Load (Route)</th>
                                        <th class="pb-4 text-center">Status</th>
                                        <th class="pb-4 text-right pr-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-white/5">
                                    <!-- DHRUV: Loop through DB results here -->
                                    <?php foreach($activeTrips as $trip): ?>
<tr class="hover:bg-white/5 transition-colors group">
    <td class="py-4 pl-2 font-medium text-white">
        #TRP-<?= $trip['trip_id'] ?>
    </td>
    <td class="py-4 text-gray-300">
        <?= $trip['license_plate'] ?>
    </td>
    <td class="py-4 text-gray-300">
        <?= $trip['full_name'] ?>
    </td>
    <td class="py-4 text-gray-300">
        <?= $trip['cargo_weight'] ?>kg
        <span class="text-xs text-gray-500 block">
            <?= $trip['origin'] ?> → <?= $trip['destination'] ?>
        </span>
    </td>
    <td class="py-4 text-center">
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-brand-gold/10 text-brand-gold border border-brand-gold/20">
            On Trip
        </span>
    </td>
</tr>
<?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="glass-panel rounded-3xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-white">7-Day Utilization Trend</h3>
                            <select class="bg-black/40 border border-white/10 text-white text-xs rounded-lg px-3 py-1.5 outline-none focus:border-brand-gold">
                                <option>This Week</option>
                                <option>Last Week</option>
                            </select>
                        </div>
                        <div class="h-64 w-full relative">
                            <!-- Canvas for Chart.js -->
                            <canvas id="utilizationChart"></canvas>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Intelligence & Alerts -->
                <div class="space-y-8">
                    

                    <!-- Alerts / Quick Insights Panel -->
                    <?php foreach($maintenanceAlerts as $alert): ?>
<div class="bg-white/5 border border-status-danger/20 rounded-xl p-3 flex gap-3">
    <div>
        <h4 class="text-sm font-medium text-white">Maintenance Pending</h4>
        <p class="text-xs text-gray-400">
            <?= $alert['license_plate'] ?> - <?= $alert['issue_description'] ?>
        </p>
    </div>
</div>
<?php endforeach; ?>

                </div>
            </div>
            
        </main>
    </div>

    <!-- Initialize Chart.js -->
     <script>
const tripCounts = <?= json_encode(array_values($tripData)); ?>;
</script>
    <script>
const tripCounts = <?= json_encode(array_values($tripData)); ?>;

document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('utilizationChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(212, 175, 55, 0.5)');
    gradient.addColorStop(1, 'rgba(212, 175, 55, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_map(function($i){
                return date('D', strtotime("-$i days"));
            }, range(6,0))); ?>,
            datasets: [{
                label: 'Completed Trips',
                data: tripCounts, // 🔥 THIS IS IMPORTANT
                borderColor: '#D4AF37',
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#0A0A0B',
                pointBorderColor: '#D4AF37',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: 'rgba(255,255,255,0.5)' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: 'rgba(255,255,255,0.5)' }
                }
            }
        }
    });
});
</script>
</body>
</html>
