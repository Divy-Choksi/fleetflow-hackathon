<?php
// ================= BACKEND PLACEHOLDER =================

// 1. Verify dispatcher session
// 2. Fetch active trips and statuses
// 3. Fetch available vehicles and capacities
// 4. Fetch available drivers and license statuses
// 5. Fetch recent system alerts/notifications
// 6. Handle quick actions (draft trip, complete trip)

// =======================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Dispatcher Dashboard</title>
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
            <!-- ACTIVE STATE: Dashboard (Dispatcher) -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand-gold/10 text-brand-gold border border-brand-gold/20 transition-all">
                <i class="ph-fill ph-squares-four text-xl"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-paper-plane-tilt text-xl"></i>
                <span class="font-medium">Create & Assign Trip</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-gas-pump text-xl"></i>
                <span class="font-medium">Fuel & Expense Entry</span>
            </a>
        </nav>
        
        <div class="p-4 border-t border-white/5">
            <a href="logout.php" class="flex items-center justify-center gap-2 w-full py-2.5 text-sm font-medium rounded-lg bg-white/5 hover:bg-status-danger/20 hover:text-status-danger text-gray-400 transition-colors">
                <i class="ph ph-sign-out text-lg"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full relative z-10 overflow-hidden">
        
        <!-- Top Header Bar -->
        <header class="h-20 glass-panel flex items-center justify-between px-8 border-b border-white/5 shrink-0">
            <h1 class="text-2xl font-semibold">Dispatcher Command Center</h1>
            <div class="flex items-center gap-6">
                <!-- Profile Actions -->
                <div class="flex items-center gap-4 pl-6 border-l border-white/10">
                    <button class="relative text-gray-400 hover:text-white transition-colors">
                        <i class="ph ph-bell text-2xl"></i>
                        <span class="absolute top-0 right-0 w-4 h-4 rounded-full bg-brand-gold text-[10px] text-black font-bold flex items-center justify-center border-2 border-[#050505]">3</span>
                    </button>
                    <div class="flex items-center gap-3 cursor-pointer group">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-white group-hover:text-brand-gold transition-colors">Sarah Connor</p>
                            <p class="text-xs text-gray-500">Chief Dispatcher</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-brand-gold/10 flex items-center justify-center border border-brand-gold/30">
                            <i class="ph-fill ph-headset text-xl text-brand-gold"></i>
                        </div>
                        <i class="ph ph-caret-down text-gray-500 text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-8 flex flex-col space-y-8">
            
            <!-- Quick Actions & Header -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-white">Operations Overview</h2>
                    <p class="text-sm text-gray-400 mt-1">Live dispatch status and resource availability.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button class="btn-outline px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i class="ph ph-gas-pump text-lg"></i> Add Fuel Entry
                    </button>
                    <button class="btn-outline px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i class="ph ph-broadcast text-lg"></i> Live Tracking
                    </button>
                    <button class="btn-premium px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
                        <i class="ph-bold ph-plus text-lg"></i> Create New Trip
                    </button>
                </div>
            </div>

            <!-- Top KPI Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="glass-card rounded-2xl p-4 border-l-2 border-l-status-info">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Active Trips</p>
                    <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                        24 <span class="text-[10px] bg-status-info/20 text-status-info px-1.5 py-0.5 rounded ml-1">On Route</span>
                    </h3>
                </div>
                <div class="glass-card rounded-2xl p-4 border-l-2 border-l-status-success">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Available Vehicles</p>
                    <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                        42 <i class="ph-bold ph-truck text-sm text-status-success"></i>
                    </h3>
                </div>
                <div class="glass-card rounded-2xl p-4 border-l-2 border-l-brand-gold">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Available Drivers</p>
                    <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                        38 <i class="ph-bold ph-users text-sm text-brand-gold"></i>
                    </h3>
                </div>
                <div class="glass-card rounded-2xl p-4 border-l-2 border-l-status-danger">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Vehicles In Shop</p>
                    <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                        8 <i class="ph-bold ph-wrench text-sm text-status-danger"></i>
                    </h3>
                </div>
                <div class="glass-card rounded-2xl p-4 border-l-2 border-l-status-success">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Completed Today</p>
                    <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                        115 <i class="ph-bold ph-check-circle text-sm text-status-success"></i>
                    </h3>
                </div>
                <div class="glass-card rounded-2xl p-4 border-l-2 border-l-status-warning bg-status-warning/5">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Pending Drafts</p>
                    <h3 class="text-2xl font-bold text-status-warning flex items-center gap-2">
                        12 <i class="ph-bold ph-clock-countdown text-sm"></i>
                    </h3>
                </div>
            </div>

            <!-- Middle Section: Active Trips (Span 2) + Alerts (Span 1) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Active Trips Table -->
                <div class="glass-panel rounded-3xl overflow-hidden flex flex-col lg:col-span-2">
                    <div class="p-6 border-b border-white/5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2"><i class="ph-fill ph-paper-plane-tilt text-status-info"></i> Dispatch Operations</h3>
                        <div class="relative w-full sm:w-64">
                            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                            <input type="text" placeholder="Search trips..." class="form-input py-1.5 pl-9 text-sm">
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-black/20 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                                    <th class="py-4 pl-6">Trip ID</th>
                                    <th class="py-4 px-4">Fleet / Vehicle</th>
                                    <th class="py-4 px-4">Driver</th>
                                    <th class="py-4 px-4">Route</th>
                                    <th class="py-4 px-4">Load / Cost</th>
                                    <th class="py-4 px-4 text-center">Status</th>
                                    <th class="py-4 pr-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-white/5">
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-3 pl-6 font-medium text-white">#TRP-8501</td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-200">Volvo VNL</p>
                                        <p class="text-[10px] text-brand-gold"><i class="ph-fill ph-truck"></i> Heavy Truck</p>
                                    </td>
                                    <td class="py-3 px-4 text-gray-300">Alex Thompson</td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-300 text-xs">Port &rarr; Hub A</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-200 text-xs">1,800 kg</p>
                                        <p class="text-gray-500 text-[10px]">₹ 4,800.00</p>
                                    </td>
                                    <td class="py-3 px-4 text-center"><span class="px-2 py-1 rounded bg-status-info/10 text-status-info text-[10px] uppercase font-bold animate-pulse">On Trip</span></td>
                                    <td class="py-3 pr-6 text-right">
                                        <button class="w-7 h-7 rounded bg-white/5 hover:bg-brand-gold hover:text-black transition-colors" title="View"><i class="ph ph-eye"></i></button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-3 pl-6 font-medium text-white">#TRP-8502</td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-200">Transit 250</p>
                                        <p class="text-[10px] text-gray-400"><i class="ph-fill ph-van"></i> Cargo Van</p>
                                    </td>
                                    <td class="py-3 px-4 text-gray-300">Marcus Reed</td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-300 text-xs">Hub A &rarr; Zone 4</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-200 text-xs">600 kg</p>
                                        <p class="text-gray-500 text-[10px]">₹ 1,200.00</p>
                                    </td>
                                    <td class="py-3 px-4 text-center"><span class="px-2 py-1 rounded bg-status-warning/10 text-status-warning text-[10px] uppercase font-bold">Draft</span></td>
                                    <td class="py-3 pr-6 text-right">
                                        <button class="w-7 h-7 rounded bg-white/5 hover:bg-status-success hover:text-white transition-colors" title="Dispatch"><i class="ph ph-paper-plane-right"></i></button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-3 pl-6 font-medium text-white">#TRP-8499</td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-200">Activa 6G</p>
                                        <p class="text-[10px] text-gray-400"><i class="ph-fill ph-motorcycle"></i> Bike</p>
                                    </td>
                                    <td class="py-3 px-4 text-gray-300">David Chen</td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-300 text-xs">Zone 4 &rarr; Local</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-200 text-xs">15 kg</p>
                                        <p class="text-gray-500 text-[10px]">₹ 150.00</p>
                                    </td>
                                    <td class="py-3 px-4 text-center"><span class="px-2 py-1 rounded bg-status-success/10 text-status-success text-[10px] uppercase font-bold">Completed</span></td>
                                    <td class="py-3 pr-6 text-right">
                                        <button class="w-7 h-7 rounded bg-white/5 hover:bg-brand-gold hover:text-black transition-colors" title="View"><i class="ph ph-eye"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Footer -->
                    <div class="mt-auto px-6 py-4 border-t border-white/5 flex items-center justify-between">
                        <p class="text-xs text-gray-500">Showing 1 to 3 of 24 trips</p>
                        <div class="flex items-center gap-1">
                            <button class="w-7 h-7 rounded bg-white/5 flex items-center justify-center text-gray-400 hover:bg-white/10"><i class="ph ph-caret-left"></i></button>
                            <button class="w-7 h-7 rounded bg-brand-gold/20 flex items-center justify-center text-brand-gold text-xs">1</button>
                            <button class="w-7 h-7 rounded bg-white/5 flex items-center justify-center text-gray-400 hover:bg-white/10 text-xs">2</button>
                            <button class="w-7 h-7 rounded bg-white/5 flex items-center justify-center text-gray-400 hover:bg-white/10"><i class="ph ph-caret-right"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Alerts Panel -->
                <div class="glass-panel rounded-3xl p-6 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2"><i class="ph-fill ph-warning text-status-warning"></i> Action Required</h3>
                        <span class="text-[10px] bg-status-danger/20 text-status-danger px-2 py-0.5 rounded font-bold">4 Alerts</span>
                    </div>
                    
                    <div class="space-y-3 overflow-y-auto pr-1 flex-1">
                        <!-- Critical Alert -->
                        <div class="bg-status-danger/5 border border-status-danger/20 rounded-xl p-3 flex gap-3">
                            <div class="w-8 h-8 rounded-lg bg-status-danger/10 flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-scales text-status-danger"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-white">Capacity Exceeded</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Draft #TRP-8504 load (2800kg) exceeds max available vehicle capacity.</p>
                            </div>
                        </div>
                        <!-- Warning Alert -->
                        <div class="bg-white/5 border border-white/10 rounded-xl p-3 flex gap-3 hover:bg-white/10 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-status-warning/10 flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-identification-card text-status-warning"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-white">License Expiring</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Driver Sarah Jenkins license expires in 2 days. Cannot assign long trips.</p>
                            </div>
                        </div>
                        <!-- Maintenance Alert -->
                        <div class="bg-white/5 border border-white/10 rounded-xl p-3 flex gap-3 hover:bg-white/10 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-status-danger/10 flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-wrench text-status-danger"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-white">Vehicle Unavailable</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Van-12 moved to maintenance. Reassign Trip #TRP-8488.</p>
                            </div>
                        </div>
                        <!-- Delay Alert -->
                        <div class="bg-white/5 border border-white/10 rounded-xl p-3 flex gap-3 hover:bg-white/10 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-status-info/10 flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-clock text-status-info"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-white">Trip Delayed</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Trip #TRP-8450 running 45 mins behind schedule due to traffic.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resource Availability Split -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Vehicle Availability -->
                <div class="glass-panel rounded-3xl overflow-hidden flex flex-col">
                    <div class="p-5 border-b border-white/5">
                        <h3 class="text-base font-semibold text-white flex items-center gap-2"><i class="ph-fill ph-truck text-brand-gold"></i> Vehicle Availability</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-black/20 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                                    <th class="py-3 pl-6">Vehicle Name</th>
                                    <th class="py-3 px-4">Type</th>
                                    <th class="py-3 px-4 text-right">Capacity</th>
                                    <th class="py-3 pr-6 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-white/5">
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-3 pl-6 font-medium text-white">Volvo VNL 860</td>
                                    <td class="py-3 px-4 text-gray-300 text-xs">Truck</td>
                                    <td class="py-3 px-4 text-right text-brand-gold font-mono text-xs">2500 kg</td>
                                    <td class="py-3 pr-6 text-center"><span class="w-2 h-2 rounded-full bg-status-success inline-block shadow-[0_0_8px_#22c55e]" title="Available"></span></td>
                                </tr>
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-3 pl-6 font-medium text-white">Ford Transit 250</td>
                                    <td class="py-3 px-4 text-gray-300 text-xs">Van</td>
                                    <td class="py-3 px-4 text-right text-brand-gold font-mono text-xs">800 kg</td>
                                    <td class="py-3 pr-6 text-center"><span class="w-2 h-2 rounded-full bg-status-info inline-block shadow-[0_0_8px_#3b82f6]" title="On Trip"></span></td>
                                </tr>
                                <tr class="hover:bg-white/5 transition-colors opacity-60">
                                    <td class="py-3 pl-6 font-medium text-white">Mercedes Sprinter</td>
                                    <td class="py-3 px-4 text-gray-300 text-xs">Van</td>
                                    <td class="py-3 px-4 text-right text-brand-gold font-mono text-xs">1200 kg</td>
                                    <td class="py-3 pr-6 text-center"><span class="w-2 h-2 rounded-full bg-status-danger inline-block shadow-[0_0_8px_#ef4444]" title="In Maintenance"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Driver Availability -->
                <div class="glass-panel rounded-3xl overflow-hidden flex flex-col">
                    <div class="p-5 border-b border-white/5">
                        <h3 class="text-base font-semibold text-white flex items-center gap-2"><i class="ph-fill ph-users text-brand-gold"></i> Driver Availability</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-black/20 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                                    <th class="py-3 pl-6">Driver Name</th>
                                    <th class="py-3 px-4 text-center">Clearance</th>
                                    <th class="py-3 px-4 text-right">License Status</th>
                                    <th class="py-3 pr-6 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-white/5">
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-3 pl-6 font-medium text-white">Alex Thompson</td>
                                    <td class="py-3 px-4 text-center text-gray-300 text-xs"><i class="ph-fill ph-truck text-brand-gold" title="Heavy Truck"></i></td>
                                    <td class="py-3 px-4 text-right text-status-success text-xs">Valid</td>
                                    <td class="py-3 pr-6 text-center"><span class="px-2 py-1 rounded bg-status-success/10 text-status-success text-[10px] uppercase font-bold">Available</span></td>
                                </tr>
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-3 pl-6 font-medium text-white">Sarah Jenkins</td>
                                    <td class="py-3 px-4 text-center text-gray-300 text-xs"><i class="ph-fill ph-motorcycle" title="Bike"></i></td>
                                    <td class="py-3 px-4 text-right text-status-danger text-xs font-bold">Expired</td>
                                    <td class="py-3 pr-6 text-center"><span class="px-2 py-1 rounded bg-status-danger/10 text-status-danger text-[10px] uppercase font-bold">Suspended</span></td>
                                </tr>
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-3 pl-6 font-medium text-white">David Chen</td>
                                    <td class="py-3 px-4 text-center text-gray-300 text-xs"><i class="ph-fill ph-van" title="Van"></i></td>
                                    <td class="py-3 px-4 text-right text-status-success text-xs">Valid</td>
                                    <td class="py-3 pr-6 text-center"><span class="px-2 py-1 rounded bg-status-info/10 text-status-info text-[10px] uppercase font-bold">On Route</span></td>
                                </tr>
                            </tbody>
                        </table>
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
                        <span class="text-brand-gold">Naivedi Binjwa</span> – Validation & Tester
                    </p>
                    <p>&copy; 2026 OptiFleet | All Rights Reserved</p>
                </div>
            </footer>
        </main>
    </div>

</body>
</html>