<?php
// ================= BACKEND PLACEHOLDER =================

// 1. Verify manager session
// 2. Fetch vehicle statistics
// 3. Fetch trip data
// 4. Fetch driver performance data
// 5. Fetch fuel cost data
// 6. Fetch maintenance cost data
// 7. Calculate utilization metrics
// 8. Generate monthly and daily summaries
// 9. Handle export to CSV/PDF

// =======================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Operational Reports</title>
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
        .glass-card:hover {
            transform: translateY(-2px);
            border-color: rgba(212, 175, 55, 0.2);
            box-shadow: 0 10px 30px -10px rgba(212, 175, 55, 0.1);
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

        /* Form Inputs */
        .form-input {
            background-color: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            color: white;
            width: 100%;
            transition: all 0.2s ease;
            outline: none;
        }
        .form-input:focus { border-color: #D4AF37; }
        select.form-input option { background-color: #0A0A0B; color: white; }
        ::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
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
        <main class="flex-1 overflow-y-auto p-8 flex flex-col space-y-8">
            
            <!-- Page Header & Export -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-white">Operational Reports & Analytics</h2>
                    <p class="text-sm text-gray-400 mt-1">Monitor performance, costs, and fleet efficiency.</p>
                </div>
                <div class="flex items-center gap-3">
                    <?php
                    // TODO: Generate CSV export
                    // TODO: Generate PDF report
                    ?>
                    <button class="btn-outline px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i class="ph ph-file-csv text-lg"></i> Export CSV
                    </button>
                    <button class="btn-premium px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
                        <i class="ph-bold ph-download-simple text-lg"></i> Download PDF
                    </button>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="glass-panel rounded-2xl p-4 flex flex-wrap items-end gap-4">
                <div class="space-y-1">
                    <label class="text-xs text-gray-400 uppercase tracking-wider font-medium">Date Range</label>
                    <div class="flex items-center gap-2">
                        <input type="date" class="form-input py-1.5 text-sm w-36">
                        <span class="text-gray-500">-</span>
                        <input type="date" class="form-input py-1.5 text-sm w-36">
                    </div>
                </div>
                <div class="space-y-1 flex-1 min-w-[120px]">
                    <label class="text-xs text-gray-400 uppercase tracking-wider font-medium">Vehicle Type</label>
                    <select class="form-input py-1.5 text-sm appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_0.7rem_center]">
                        <option>All Types</option>
                        <option>Truck</option>
                        <option>Van</option>
                        <option>Bike</option>
                    </select>
                </div>
                <div class="space-y-1 flex-1 min-w-[120px]">
                    <label class="text-xs text-gray-400 uppercase tracking-wider font-medium">Status</label>
                    <select class="form-input py-1.5 text-sm appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_0.7rem_center]">
                        <option>All Status</option>
                        <option>Completed</option>
                        <option>On Trip</option>
                        <option>Cancelled</option>
                    </select>
                </div>
                <button class="bg-white/10 hover:bg-brand-gold hover:text-black text-white px-5 py-1.5 h-[38px] rounded-xl text-sm font-medium transition-colors">
                    Apply Filters
                </button>
            </div>

            <!-- Top KPI Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="glass-card rounded-2xl p-4">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Trips Today</p>
                    <h3 class="text-2xl font-bold text-status-success flex items-center gap-2">
                        128 <i class="ph-bold ph-trend-up text-sm"></i>
                    </h3>
                </div>
                <div class="glass-card rounded-2xl p-4">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Active Vehicles</p>
                    <h3 class="text-2xl font-bold text-status-success flex items-center gap-2">
                        85 <span class="text-[10px] bg-status-success/20 text-status-success px-1.5 py-0.5 rounded ml-1">Optimal</span>
                    </h3>
                </div>
                <div class="glass-card rounded-2xl p-4 border border-brand-gold/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-brand-gold/5"></div>
                    <div class="relative z-10">
                        <p class="text-xs text-brand-gold font-medium uppercase tracking-wider mb-1">Fleet Utilization</p>
                        <h3 class="text-2xl font-bold text-white">82%</h3>
                    </div>
                </div>
                <div class="glass-card rounded-2xl p-4">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Fuel Cost (Today)</p>
                    <h3 class="text-2xl font-bold text-status-warning flex items-center gap-2">
                        ₹ 12.4k <i class="ph-bold ph-trend-up text-sm"></i>
                    </h3>
                </div>
                <div class="glass-card rounded-2xl p-4">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Maint. Cost (MTD)</p>
                    <h3 class="text-2xl font-bold text-status-danger flex items-center gap-2">
                        ₹ 45.2k <i class="ph-bold ph-warning-circle text-sm"></i>
                    </h3>
                </div>
                <div class="glass-card rounded-2xl p-4">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Est. Revenue</p>
                    <h3 class="text-2xl font-bold text-status-success flex items-center gap-2">
                        ₹ 145k <i class="ph-bold ph-trend-up text-sm"></i>
                    </h3>
                </div>
            </div>

            <!-- Fleet Utilization & Cost Analysis (2 Columns) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Utilization Section -->
                <div class="glass-panel rounded-3xl p-6 flex flex-col justify-center relative overflow-hidden">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="ph-fill ph-chart-pie-slice text-brand-gold text-xl"></i>
                        <h3 class="text-lg font-semibold text-white">Fleet Utilization Metrics</h3>
                    </div>
                    <?php // TODO: Calculate fleet utilization ?>
                    <p class="text-sm text-gray-400 mb-6 font-mono bg-black/30 p-2 rounded inline-block w-fit">
                        Formula: (On Trip / Total Fleet) × 100
                    </p>
                    <div class="flex items-end justify-between mb-2">
                        <span class="text-4xl font-bold text-white">81.7%</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-status-success/10 text-status-success border border-status-success/20">Good</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-3 mb-2">
                        <div class="bg-gradient-to-r from-brand-gold to-status-success h-3 rounded-full" style="width: 81.7%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 font-medium">
                        <span>0% (Underutilized)</span>
                        <span>85 / 104 Vehicles Active</span>
                        <span>100% (Max)</span>
                    </div>
                </div>

                <!-- Cost Analysis Section -->
                <div class="glass-panel rounded-3xl p-6">
                    <div class="flex items-center gap-2 mb-6">
                        <i class="ph-fill ph-coins text-brand-gold text-xl"></i>
                        <h3 class="text-lg font-semibold text-white">Cost Analysis Summary</h3>
                    </div>
                    <?php // TODO: Calculate fuel and maintenance cost summaries ?>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                            <p class="text-xs text-gray-400 mb-1">Monthly Fuel Cost</p>
                            <p class="text-lg font-semibold text-white">₹ 284,500</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                            <p class="text-xs text-gray-400 mb-1">Monthly Maintenance</p>
                            <p class="text-lg font-semibold text-status-warning">₹ 45,200</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4 border border-white/5 col-span-2 flex justify-between items-center bg-gradient-to-r from-brand-gold/10 to-transparent">
                            <div>
                                <p class="text-xs text-brand-gold font-medium mb-1">Average Cost Per Trip</p>
                                <p class="text-xs text-gray-500 font-mono">(Total Cost / Total Trips)</p>
                            </div>
                            <p class="text-2xl font-bold text-white">₹ 854.20</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Line Chart: Trips Per Day -->
                <div class="glass-panel rounded-3xl p-6 lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-semibold text-white flex items-center gap-2"><i class="ph-fill ph-trend-up text-brand-gold"></i> Trips per Day (Last 7 Days)</h3>
                    </div>
                    <div class="h-64 w-full relative">
                        <canvas id="tripsChart"></canvas>
                    </div>
                </div>
                <!-- Pie/Doughnut Chart: Vehicle Usage -->
                <div class="glass-panel rounded-3xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-semibold text-white flex items-center gap-2"><i class="ph-fill ph-truck text-brand-gold"></i> Usage by Type</h3>
                    </div>
                    <div class="h-64 w-full relative flex items-center justify-center">
                        <canvas id="usageChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Data Tables Section (Tab-like structure visually separated) -->
            <div class="space-y-8">
                
                <!-- Trip Reports Table -->
                <div class="glass-panel rounded-3xl overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-white/5 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2"><i class="ph-fill ph-paper-plane-tilt text-status-info"></i> Trip Reports</h3>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-white/10 text-white text-xs rounded-lg cursor-pointer">Today</span>
                            <span class="px-3 py-1 text-gray-500 text-xs hover:text-white cursor-pointer">This Week</span>
                            <span class="px-3 py-1 text-gray-500 text-xs hover:text-white cursor-pointer">This Month</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-black/20 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                    <th class="py-4 pl-6">Trip ID / Date</th>
                                    <th class="py-4 px-4">Vehicle</th>
                                    <th class="py-4 px-4">Driver</th>
                                    <th class="py-4 px-4">Route (Origin &rarr; Dest)</th>
                                    <th class="py-4 px-4 text-right">Load</th>
                                    <th class="py-4 px-4 text-right">Est. Fuel Cost</th>
                                    <th class="py-4 pr-6 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-white/5">
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-4 pl-6"><p class="font-medium text-white">#TRP-8492</p><p class="text-[10px] text-gray-500">Today, 10:45 AM</p></td>
                                    <td class="py-4 px-4 text-gray-300">Volvo VNL 860</td>
                                    <td class="py-4 px-4 text-gray-300">Alex Thompson</td>
                                    <td class="py-4 px-4 text-gray-300">Port Terminal &rarr; City Hub</td>
                                    <td class="py-4 px-4 text-right text-gray-300">1,800 kg</td>
                                    <td class="py-4 px-4 text-right text-gray-300">₹ 4,800.00</td>
                                    <td class="py-4 pr-6 text-center"><span class="px-2 py-1 rounded bg-status-success/10 text-status-success text-[10px] uppercase font-bold">Completed</span></td>
                                </tr>
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-4 pl-6"><p class="font-medium text-white">#TRP-8493</p><p class="text-[10px] text-gray-500">Today, 11:30 AM</p></td>
                                    <td class="py-4 px-4 text-gray-300">Ford Transit 250</td>
                                    <td class="py-4 px-4 text-gray-300">Marcus Reed</td>
                                    <td class="py-4 px-4 text-gray-300">Warehouse A &rarr; Zone 4</td>
                                    <td class="py-4 px-4 text-right text-gray-300">600 kg</td>
                                    <td class="py-4 px-4 text-right text-gray-300">₹ 1,200.00</td>
                                    <td class="py-4 pr-6 text-center"><span class="px-2 py-1 rounded bg-status-warning/10 text-status-warning text-[10px] uppercase font-bold">On Trip</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Driver & Vehicle Performance Split -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Driver Performance -->
                    <div class="glass-panel rounded-3xl overflow-hidden flex flex-col">
                        <div class="p-6 border-b border-white/5">
                            <?php // TODO: Calculate driver performance metrics ?>
                            <h3 class="text-base font-semibold text-white flex items-center gap-2"><i class="ph-fill ph-users text-brand-gold"></i> Driver Performance</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse whitespace-nowrap">
                                <thead>
                                    <tr class="bg-black/20 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                                        <th class="py-3 pl-6">Driver Name</th>
                                        <th class="py-3 px-4 text-center">Trips</th>
                                        <th class="py-3 px-4 text-center">License</th>
                                        <th class="py-3 pr-6 text-right">Perf. Score</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-white/5">
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="py-3 pl-6 font-medium text-white flex items-center gap-2">
                                            <div class="w-6 h-6 rounded bg-brand-gold/20 text-brand-gold flex items-center justify-center text-xs">AT</div> Alex Thompson
                                        </td>
                                        <td class="py-3 px-4 text-center text-gray-300">45</td>
                                        <td class="py-3 px-4 text-center"><i class="ph-fill ph-check-circle text-status-success"></i></td>
                                        <td class="py-3 pr-6 text-right text-brand-gold font-bold">98%</td>
                                    </tr>
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="py-3 pl-6 font-medium text-white flex items-center gap-2">
                                            <div class="w-6 h-6 rounded bg-white/10 text-gray-300 flex items-center justify-center text-xs">MR</div> Marcus Reed
                                        </td>
                                        <td class="py-3 px-4 text-center text-gray-300">38</td>
                                        <td class="py-3 px-4 text-center"><i class="ph-fill ph-warning-circle text-status-warning" title="Expiring Soon"></i></td>
                                        <td class="py-3 pr-6 text-right text-brand-gold font-bold">92%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Vehicle Performance -->
                    <div class="glass-panel rounded-3xl overflow-hidden flex flex-col">
                        <div class="p-6 border-b border-white/5">
                            <h3 class="text-base font-semibold text-white flex items-center gap-2"><i class="ph-fill ph-truck text-brand-gold"></i> Vehicle Performance</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse whitespace-nowrap">
                                <thead>
                                    <tr class="bg-black/20 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                                        <th class="py-3 pl-6">Vehicle (Plate)</th>
                                        <th class="py-3 px-4 text-center">Distance</th>
                                        <th class="py-3 px-4 text-right">Fuel Cost</th>
                                        <th class="py-3 pr-6 text-right">Maint. Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-white/5">
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="py-3 pl-6">
                                            <p class="font-medium text-white">Volvo VNL</p>
                                            <p class="text-[10px] text-gray-500 font-mono">XYZ-7741</p>
                                        </td>
                                        <td class="py-3 px-4 text-center text-gray-300">12k km</td>
                                        <td class="py-3 px-4 text-right text-gray-300">₹ 85k</td>
                                        <td class="py-3 pr-6 text-right text-status-danger font-medium">₹ 12.5k</td>
                                    </tr>
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="py-3 pl-6">
                                            <p class="font-medium text-white">Ford Transit</p>
                                            <p class="text-[10px] text-gray-500 font-mono">DEF-9012</p>
                                        </td>
                                        <td class="py-3 px-4 text-center text-gray-300">8.5k km</td>
                                        <td class="py-3 px-4 text-right text-gray-300">₹ 42k</td>
                                        <td class="py-3 pr-6 text-right text-status-success font-medium">₹ 2.2k</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Custom Footer -->
            <footer class="mt-8 pt-6 border-t border-white/5 pb-4 shrink-0">
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

    <!-- Chart.js Logic -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // Shared Chart Options
            Chart.defaults.color = 'rgba(255, 255, 255, 0.5)';
            Chart.defaults.font.family = "'Outfit', sans-serif";
            
            // 1. Line Chart: Trips Per Day
            const tripsCtx = document.getElementById('tripsChart').getContext('2d');
            const tripsGradient = tripsCtx.createLinearGradient(0, 0, 0, 400);
            tripsGradient.addColorStop(0, 'rgba(212, 175, 55, 0.5)'); // Brand Gold
            tripsGradient.addColorStop(1, 'rgba(212, 175, 55, 0.0)');

            new Chart(tripsCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Total Trips',
                        data: [112, 118, 135, 120, 142, 110, 85],
                        borderColor: '#D4AF37',
                        backgroundColor: tripsGradient,
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
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(10, 10, 11, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#D4AF37',
                            borderColor: 'rgba(212, 175, 55, 0.3)',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: { grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false } },
                        y: { 
                            grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                            beginAtZero: true 
                        }
                    },
                    interaction: { intersect: false, mode: 'index' }
                }
            });

            // 2. Doughnut Chart: Vehicle Usage Distribution
            const usageCtx = document.getElementById('usageChart').getContext('2d');
            new Chart(usageCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Heavy Trucks', 'Cargo Vans', 'Delivery Bikes'],
                    datasets: [{
                        data: [45, 35, 20],
                        backgroundColor: [
                            '#D4AF37', // Brand Gold
                            '#3b82f6', // Info Blue
                            '#22c55e'  // Success Green
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(10, 10, 11, 0.9)',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${context.raw}%`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
