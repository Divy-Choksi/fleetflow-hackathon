<?php
// ================= BACKEND PLACEHOLDER =================
// 1. Verify manager session and role
// 2. Fetch available vehicles (status = available)
// 3. Fetch available drivers (status = on_duty)
// 4. Handle trip creation (POST)
// 5. Update vehicle status to on_trip
// 6. Save estimated fuel cost
// 7. Fetch active trips
// 8. Handle trip completion and cancellation
// =======================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Trip Dispatcher</title>
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
        .btn-premium:disabled { 
            background: rgba(255, 255, 255, 0.05); 
            color: rgba(255, 255, 255, 0.4); 
            border: 1px solid rgba(255, 255, 255, 0.1);
            cursor: not-allowed; 
            box-shadow: none; 
        }
        
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
        .form-input:focus { border-color: #D4AF37; box-shadow: 0 0 10px rgba(212, 175, 55, 0.1); }
        .form-input.input-error { border-color: #ef4444; }
        .form-input.input-error:focus { box-shadow: 0 0 0 1px #ef4444; }

        select.form-input option {
            background-color: #0A0A0B;
            color: white;
        }
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
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-squares-four text-xl"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="manager_vehicle_registry.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-truck text-xl"></i>
                <span class="font-medium">Vehicle Registry</span>
            </a>
            
            <a href="manager_drivers.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-users text-xl"></i>
                <span class="font-medium">Drivers</span>
            </a>

            <a href="manager_register.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-user-plus text-xl"></i>
                <span class="font-medium">Registration</span>
            </a>
            
            <!-- ACTIVE STATE: Trip Dispatcher -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand-gold/10 text-brand-gold border border-brand-gold/20 transition-all">
                <i class="ph-fill ph-map-pin-line text-xl"></i>
                <span class="font-medium">Trip Dispatcher</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all flex-wrap relative">
                <i class="ph ph-wrench text-xl"></i>
                <span class="font-medium">Maintenance</span>
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
                    <a href="logout.php" class="text-gray-500 hover:text-status-danger transition-colors ml-2" title="Logout">
                        <i class="ph ph-sign-out text-2xl"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-8 flex flex-col">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-white">Trip Dispatcher</h2>
                <p class="text-sm text-gray-400 mt-1">Create, assign, and monitor delivery trips efficiently.</p>
            </div>

            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <!-- Card 1 -->
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-brand-gold/10 flex items-center justify-center border border-brand-gold/20 text-brand-gold group-hover:bg-brand-gold/20 transition-colors">
                        <i class="ph-fill ph-paper-plane-tilt text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white leading-tight">45</h3>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Active Trips</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-status-info/10 flex items-center justify-center border border-status-info/20 text-status-info group-hover:bg-status-info/20 transition-colors">
                        <i class="ph-fill ph-clock-countdown text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white leading-tight">12</h3>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Pending Dispatch</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-status-success/10 flex items-center justify-center border border-status-success/20 text-status-success group-hover:bg-status-success/20 transition-colors">
                        <i class="ph-fill ph-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white leading-tight">128</h3>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Completed Today</p>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 text-gray-300 group-hover:bg-white/10 transition-colors">
                        <i class="ph-fill ph-truck text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white leading-tight">85</h3>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Available Vehicles</p>
                    </div>
                </div>
            </div>

            <!-- PROPER Create & Assign Trip Form -->
            <?php
            // ================= DISPATCH BACKEND PLACEHOLDER =================
            // 1. Fetch available vehicles (status = available)
            // 2. Fetch available drivers (status = on_duty)
            // 3. Validate cargo weight vs capacity
            // 4. Validate driver license expiry
            // 5. Calculate estimated fuel cost
            // 6. Insert trip record
            // 7. Update vehicle status to 'on_trip'
            // 8. Update driver status to 'assigned'
            // ===============================================================
            ?>
            <div class="glass-panel rounded-3xl p-8 mb-8 border-white/5 relative overflow-visible">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-gold/5 via-transparent to-transparent pointer-events-none rounded-3xl"></div>
                
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-brand-gold/10 flex items-center justify-center border border-brand-gold/20 shadow-[0_0_15px_rgba(212,175,55,0.15)]">
                            <i class="ph-fill ph-rocket-launch text-2xl text-brand-gold"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">Create & Dispatch Trip</h3>
                            <p class="text-sm text-gray-400 mt-0.5">Assign vehicle and driver with smart capacity validation</p>
                        </div>
                    </div>
                </div>

                <form id="dispatchForm" method="POST" action="" class="relative z-10" novalidate>
                    <!-- 2-Column Responsive Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        
                        <!-- LEFT COLUMN: Route & Load Details -->
                        <div class="space-y-6">
                            <h4 class="text-sm font-medium text-brand-gold uppercase tracking-widest mb-4"><i class="ph-fill ph-package mr-1"></i> Cargo & Route</h4>
                            
                            <!-- Cargo Weight -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="tWeight">Cargo Weight (kg)</label>
                                <div class="relative">
                                    <i class="ph ph-scales absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 group-focus-within:text-brand-gold transition-colors"></i>
                                    <input type="number" id="tWeight" name="cargo_weight" placeholder="e.g. 1200" class="form-input pl-10" min="1" required>
                                </div>
                                <p id="weightWarning" class="text-status-danger text-[11px] hidden mt-1 flex items-center gap-1 font-medium bg-status-danger/10 p-2 rounded-lg border border-status-danger/20">
                                    <i class="ph-fill ph-warning-circle text-lg"></i> No available vehicle can handle this load. Please reduce weight or add a higher-capacity vehicle.
                                </p>
                            </div>

                            <!-- Pickup Location -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="tOrigin">Pickup Location (Origin)</label>
                                <div class="relative">
                                    <i class="ph ph-map-pin absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 group-focus-within:text-brand-gold transition-colors"></i>
                                    <input type="text" id="tOrigin" name="origin" placeholder="Enter origin address/hub" class="form-input pl-10" required>
                                </div>
                                <p class="text-status-danger text-[11px] hidden error-msg">Origin is required.</p>
                            </div>

                            <!-- Drop Location -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="tDest">Drop Location (Destination)</label>
                                <div class="relative">
                                    <i class="ph ph-flag-checkered absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 group-focus-within:text-brand-gold transition-colors"></i>
                                    <input type="text" id="tDest" name="destination" placeholder="Enter destination address" class="form-input pl-10" required>
                                </div>
                                <p class="text-status-danger text-[11px] hidden error-msg">Destination is required.</p>
                            </div>

                            <!-- Estimated Distance -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="tDistance">Estimated Distance (km)</label>
                                <div class="relative">
                                    <i class="ph ph-navigation-arrow absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 group-focus-within:text-brand-gold transition-colors"></i>
                                    <input type="number" id="tDistance" name="distance" placeholder="e.g. 145" class="form-input pl-10" min="1" required>
                                </div>
                                <p class="text-status-danger text-[11px] hidden error-msg">Valid distance is required.</p>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Asset Assignment -->
                        <div class="space-y-6">
                            <h4 class="text-sm font-medium text-brand-gold uppercase tracking-widest mb-4"><i class="ph-fill ph-steering-wheel mr-1"></i> Asset Assignment</h4>

                            <!-- Vehicle Type -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="tVehicleType">Vehicle Type</label>
                                <div class="relative">
                                    <i class="ph ph-list-dashes absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10 group-focus-within:text-brand-gold transition-colors"></i>
                                    <select id="tVehicleType" name="vehicle_type" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                        <option value="" disabled selected>Filter by type</option>
                                        <option value="Truck">Truck (Max 2500kg)</option>
                                        <option value="Van">Van (Max 800kg)</option>
                                        <option value="Bike">Bike (Max 50kg)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Available Vehicle -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="tVehicle">Available Vehicle</label>
                                <div class="relative">
                                    <?php
                                    // TODO: Fetch available vehicles where:
                                    // status = available
                                    // capacity >= cargo_weight
                                    // vehicle_type = selected type
                                    // ORDER BY capacity ASC
                                    ?>
                                    <i class="ph ph-truck absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10 group-focus-within:text-brand-gold transition-colors"></i>
                                    <select id="tVehicle" name="vehicle_id" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                        <option value="" disabled selected>Select specific vehicle</option>
                                        <option value="v1" data-capacity="2500" data-type="Truck">Volvo VNL 860 (Truck)</option>
                                        <option value="v2" data-capacity="800" data-type="Van">Ford Transit 250 (Van)</option>
                                        <option value="v3" data-capacity="50" data-type="Bike">Honda Activa 6G (Bike)</option>
                                    </select>
                                </div>
                                <p class="text-status-danger text-[11px] hidden error-msg">Vehicle is required.</p>
                            </div>

                            <!-- Smart Suggestion Display Area -->
                            <div id="smartSuggestionBox" class="hidden bg-brand-gold/10 border border-brand-gold/30 rounded-xl p-3 flex flex-col gap-1 transition-all duration-300">
                                <div class="flex items-center gap-2 text-brand-gold font-medium">
                                    <i class="ph-fill ph-magic-wand text-lg"></i>
                                    <span id="suggestionText">Optimal Match: Van-05</span>
                                </div>
                                <div class="w-full bg-black/40 rounded-full h-2 mt-2 border border-white/5 relative overflow-hidden">
                                    <div id="utilizationBar" class="bg-gradient-to-r from-brand-gold to-yellow-400 h-2 rounded-full transition-all duration-500 w-0"></div>
                                </div>
                                <p id="utilizationText" class="text-xs text-gray-300 mt-1 text-right font-mono">Load Utilization: 0%</p>
                            </div>

                            <!-- Available Driver -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="tDriver">Available Driver</label>
                                <div class="relative">
                                    <?php
                                    // TODO: Fetch drivers where status = on_duty AND license_expiry >= today
                                    ?>
                                    <i class="ph ph-identification-card absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10 group-focus-within:text-brand-gold transition-colors"></i>
                                    <select id="tDriver" name="driver_id" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                        <option value="" disabled selected>Select on-duty driver</option>
                                        <option value="d1" data-expired="false">Alex Thompson</option>
                                        <option value="d2" data-expired="false">Marcus Reed</option>
                                        <option value="d3" data-expired="true" class="text-gray-500">Sarah Jenkins (Expired)</option>
                                    </select>
                                </div>
                                <p id="driverWarning" class="text-status-danger text-[11px] hidden error-msg flex items-center gap-1">
                                    <i class="ph-fill ph-warning-circle"></i> Selected driver license is expired!
                                </p>
                            </div>

                            <!-- Estimated Fuel Cost (Auto / Manual) -->
                            <div class="space-y-2 relative group">
                                <div class="flex justify-between items-end">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="tFuelCost">Est. Fuel Cost (₹)</label>
                                    <span class="text-[10px] text-brand-gold bg-brand-gold/10 px-2 py-0.5 rounded border border-brand-gold/20">Auto-Calculated</span>
                                </div>
                                <div class="relative">
                                    <?php
                                    // TODO: Calculate fuel cost based on vehicle mileage and distance
                                    ?>
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">₹</span>
                                    <input type="number" id="tFuelCost" name="estimated_fuel_cost" placeholder="0.00" min="0" step="0.01" class="form-input pl-8 font-mono text-brand-gold focus:text-white transition-colors" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Area (Buttons & Status) -->
                    <div class="pt-8 mt-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <div id="formStatusIcon" class="w-2 h-2 rounded-full bg-status-danger animate-pulse"></div>
                            <span id="formStatusText">Pending validations...</span>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <button type="reset" id="resetBtn" class="btn-outline px-6 py-3 rounded-xl text-sm font-medium w-full sm:w-auto flex items-center justify-center gap-2">
                                <i class="ph ph-arrow-counter-clockwise"></i> Reset
                            </button>
                            <button type="submit" name="dispatch_trip" id="submitDispatchBtn" class="btn-premium px-8 py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2 whitespace-nowrap w-full sm:w-auto" disabled>
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> Create & Dispatch
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Live Dispatch Tracking Table -->
            <div class="mb-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-status-warning/10 flex items-center justify-center border border-status-warning/20">
                    <i class="ph-fill ph-broadcast text-status-warning animate-pulse"></i>
                </div>
                <h3 class="text-lg font-semibold text-white">Live Dispatch Tracking</h3>
            </div>
            
            <div class="glass-panel rounded-3xl overflow-hidden flex-1 flex flex-col min-h-[300px]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white/5 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-white/10">
                                <th class="py-4 pl-6">Trip ID</th>
                                <th class="py-4 px-4">Vehicle Details</th>
                                <th class="py-4 px-4">Driver</th>
                                <th class="py-4 px-4 text-right">Load</th>
                                <th class="py-4 px-4">Route</th>
                                <th class="py-4 px-4 text-right">Est. Fuel Cost</th>
                                <th class="py-4 px-4 text-center">Status</th>
                                <th class="py-4 pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white/5">
                            
                            <!-- Dispatched Row -->
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-6 font-medium text-white">#TRP-8492</td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-200">Volvo VNL 860</p>
                                    <div class="flex items-center gap-1 mt-1">
                                        <span class="inline-flex items-center gap-1 text-gray-400 bg-white/5 px-1.5 py-0.5 rounded text-[10px] border border-white/10">
                                            <i class="ph-fill ph-truck text-brand-gold"></i> Truck
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-gray-300">Alex Thompson</td>
                                <td class="py-4 px-4 text-right text-gray-300 font-medium">1,800 kg</td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-300"><span class="text-gray-500">From:</span> Port Terminal</p>
                                    <p class="text-gray-300"><span class="text-gray-500">To:</span> City Center Hub</p>
                                </td>
                                <td class="py-4 px-4 text-right text-gray-300 font-medium">₹ 4,800.00</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-status-warning/10 text-status-warning border border-status-warning/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-status-warning mr-1.5 animate-pulse"></span> Dispatched
                                    </span>
                                </td>
                                <td class="py-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-status-success hover:text-white flex items-center justify-center text-gray-400 transition-colors" title="Mark Completed">
                                            <i class="ph ph-check-circle text-lg"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-status-danger hover:text-white flex items-center justify-center text-gray-400 transition-colors" title="Cancel Trip">
                                            <i class="ph ph-x-circle text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Completed Row -->
                            <tr class="hover:bg-white/5 transition-colors group opacity-70">
                                <td class="py-4 pl-6 font-medium text-white">#TRP-8491</td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-200">Ford Transit 250</p>
                                    <div class="flex items-center gap-1 mt-1">
                                        <span class="inline-flex items-center gap-1 text-gray-400 bg-white/5 px-1.5 py-0.5 rounded text-[10px] border border-white/10">
                                            <i class="ph-fill ph-van text-status-info"></i> Van
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-gray-300">Marcus Reed</td>
                                <td class="py-4 px-4 text-right text-gray-300 font-medium">450 kg</td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-300"><span class="text-gray-500">From:</span> Warehouse A</p>
                                    <p class="text-gray-300"><span class="text-gray-500">To:</span> Hub B</p>
                                </td>
                                <td class="py-4 px-4 text-right text-gray-300 font-medium">₹ 1,250.00</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-status-success/10 text-status-success border border-status-success/20">
                                        Completed
                                    </span>
                                </td>
                                <td class="py-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-brand-gold hover:text-black flex items-center justify-center text-gray-400 transition-colors" title="View Log">
                                            <i class="ph ph-file-text text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Cancelled Row -->
                            <tr class="hover:bg-white/5 transition-colors group opacity-70">
                                <td class="py-4 pl-6 font-medium text-white">#TRP-8490</td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-200">Honda Activa 6G</p>
                                    <div class="flex items-center gap-1 mt-1">
                                        <span class="inline-flex items-center gap-1 text-gray-400 bg-white/5 px-1.5 py-0.5 rounded text-[10px] border border-white/10">
                                            <i class="ph-fill ph-motorcycle text-gray-400"></i> Bike
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-gray-300">Sarah Jenkins</td>
                                <td class="py-4 px-4 text-right text-gray-300 font-medium">15 kg</td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-300"><span class="text-gray-500">From:</span> Hub B</p>
                                    <p class="text-gray-300"><span class="text-gray-500">To:</span> Local Delivery</p>
                                </td>
                                <td class="py-4 px-4 text-right text-gray-300 font-medium">₹ 150.00</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-status-danger/10 text-status-danger border border-status-danger/20">
                                        Cancelled
                                    </span>
                                </td>
                                <td class="py-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-brand-gold hover:text-black flex items-center justify-center text-gray-400 transition-colors" title="View Reason">
                                            <i class="ph ph-info text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
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

    <!-- Frontend Form Validation, Smart Suggestions & Auto-Calculations -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            const form = document.getElementById('dispatchForm');
            const btnSubmit = document.getElementById('submitDispatchBtn');
            const resetBtn = document.getElementById('resetBtn');
            
            const inputs = {
                weight: document.getElementById('tWeight'),
                origin: document.getElementById('tOrigin'),
                dest: document.getElementById('tDest'),
                distance: document.getElementById('tDistance'),
                vType: document.getElementById('tVehicleType'),
                vehicle: document.getElementById('tVehicle'),
                driver: document.getElementById('tDriver'),
                fuelCost: document.getElementById('tFuelCost')
            };

            // UI Elements
            const weightWarning = document.getElementById('weightWarning');
            const driverWarning = document.getElementById('driverWarning');
            const smartSuggestionBox = document.getElementById('smartSuggestionBox');
            const suggestionText = document.getElementById('suggestionText');
            const utilizationBar = document.getElementById('utilizationBar');
            const utilizationText = document.getElementById('utilizationText');
            
            const statusIcon = document.getElementById('formStatusIcon');
            const statusText = document.getElementById('formStatusText');

            // Mock Backend Data limits & factors
            const MAX_CAPACITY_OVERALL = 2500; 
            const FUEL_RATE_PER_KM = 25; // Base price factor
            
            const vehicleData = {
                'v1': { name: 'Volvo VNL 860', capacity: 2500, type: 'Truck', factor: 0.35 },
                'v2': { name: 'Ford Transit 250', capacity: 800, type: 'Van', factor: 0.15 },
                'v3': { name: 'Honda Activa 6G', capacity: 50, type: 'Bike', factor: 0.05 }
            };

            // Calculate auto fuel cost
            function calculateFuel() {
                const dist = parseFloat(inputs.distance.value) || 0;
                const vId = inputs.vehicle.value;
                if(dist > 0 && vId && vehicleData[vId]) {
                    const factor = vehicleData[vId].factor;
                    const estimatedCost = (dist * FUEL_RATE_PER_KM * factor).toFixed(2);
                    inputs.fuelCost.value = estimatedCost;
                }
            }

            // Update Smart Suggestion Box
            function updateSmartSuggestion() {
                const weight = parseFloat(inputs.weight.value) || 0;
                const vId = inputs.vehicle.value;
                
                // Hide if no vehicle selected or invalid weight
                if(!vId || weight <= 0 || !vehicleData[vId]) {
                    smartSuggestionBox.classList.add('hidden');
                    return;
                }

                const vData = vehicleData[vId];
                
                if (weight > vData.capacity) {
                    smartSuggestionBox.classList.add('hidden');
                    return; // Handled by heavy load warning
                }

                // Show suggestion
                smartSuggestionBox.classList.remove('hidden');
                suggestionText.textContent = `Optimal Match: ${vData.name} (Max: ${vData.capacity}kg)`;
                
                // Calculate Utilization %
                let utilPct = (weight / vData.capacity) * 100;
                utilPct = Math.min(Math.max(utilPct, 0), 100); // Clamp 0-100
                
                // Update UI Bar
                utilizationBar.style.width = `${utilPct}%`;
                utilizationText.textContent = `Load Utilization: ${Math.round(utilPct)}%`;
                
                // Color Code Bar
                utilizationBar.className = 'h-2 rounded-full transition-all duration-500 ';
                if(utilPct > 90) utilizationBar.classList.add('bg-status-danger');
                else if(utilPct > 60) utilizationBar.classList.add('bg-brand-gold');
                else utilizationBar.classList.add('bg-status-success');
            }

            // Validations
            const validators = {
                weight: (val) => {
                    const w = parseFloat(val);
                    const vId = inputs.vehicle.value;
                    let maxCap = MAX_CAPACITY_OVERALL;
                    
                    if(vId && vehicleData[vId]) maxCap = vehicleData[vId].capacity;
                    
                    if (isNaN(w) || w <= 0) return false;
                    
                    // Capacity Check
                    if (w > maxCap) {
                        inputs.weight.classList.add('input-error');
                        weightWarning.classList.remove('hidden');
                        return false;
                    } else {
                        inputs.weight.classList.remove('input-error');
                        weightWarning.classList.add('hidden');
                        return true;
                    }
                },
                origin: (val) => val.trim().length >= 3,
                dest: (val) => val.trim().length >= 3,
                distance: (val) => !isNaN(val) && parseFloat(val) > 0,
                vType: (val) => true, // Optional filter
                vehicle: (val) => val !== "",
                driver: (val) => {
                    if (val === "") {
                        driverWarning.classList.add('hidden');
                        return false;
                    }
                    const selectedOpt = inputs.driver.options[inputs.driver.selectedIndex];
                    const isExpired = selectedOpt.getAttribute('data-expired') === 'true';
                    
                    if(isExpired) {
                        inputs.driver.classList.add('input-error');
                        driverWarning.classList.remove('hidden');
                        return false;
                    } else {
                        inputs.driver.classList.remove('input-error');
                        driverWarning.classList.add('hidden');
                        return true;
                    }
                },
                fuelCost: (val) => !isNaN(val) && parseFloat(val) > 0
            };

            function validateField(inputElement, fieldKey) {
                if(fieldKey === 'weight' || fieldKey === 'driver') {
                    // special handling above
                    return validators[fieldKey](inputElement.value); 
                }

                const isValid = validators[fieldKey](inputElement.value);
                const errorContainer = inputElement.closest('.group');
                const errorMsg = errorContainer ? errorContainer.querySelector('.error-msg') : null;
                
                if (!isValid && inputElement.value !== "") {
                    inputElement.classList.add('input-error');
                    if(errorMsg) errorMsg.classList.remove('hidden');
                } else {
                    inputElement.classList.remove('input-error');
                    if(errorMsg) errorMsg.classList.add('hidden');
                }
                
                return isValid;
            }

            function checkFormValidity() {
                let isFormValid = true;
                
                // Must validate all in sequence to catch cross-dependencies
                for (const key in inputs) {
                    if (!validators[key](inputs[key].value)) {
                        isFormValid = false;
                    }
                }
                
                btnSubmit.disabled = !isFormValid;
                
                // Update Status UI
                if(isFormValid) {
                    statusIcon.classList.replace('bg-status-danger', 'bg-status-success');
                    statusIcon.classList.remove('animate-pulse');
                    statusText.textContent = "Ready for dispatch";
                    statusText.classList.replace('text-gray-500', 'text-status-success');
                } else {
                    statusIcon.classList.replace('bg-status-success', 'bg-status-danger');
                    statusIcon.classList.add('animate-pulse');
                    statusText.textContent = "Pending validations...";
                    statusText.classList.replace('text-status-success', 'text-gray-500');
                }
            }

            // Triggers
            inputs.distance.addEventListener('input', () => { calculateFuel(); checkFormValidity(); });
            inputs.vehicle.addEventListener('change', () => { 
                calculateFuel(); 
                validators.weight(inputs.weight.value); // Re-check capacity for new vehicle
                updateSmartSuggestion();
                checkFormValidity(); 
            });
            inputs.weight.addEventListener('input', () => {
                validators.weight(inputs.weight.value);
                updateSmartSuggestion();
                checkFormValidity();
            });

            // General listeners
            for (const key in inputs) {
                const checkAndUpdate = (e) => {
                    if(key !== 'weight' && key !== 'vehicle' && key !== 'distance') {
                        validateField(e.target, key);
                        checkFormValidity();
                    }
                };
                inputs[key].addEventListener('input', checkAndUpdate);
                inputs[key].addEventListener('change', checkAndUpdate);
            }

            // Reset behavior
            resetBtn.addEventListener('click', () => {
                setTimeout(() => {
                    smartSuggestionBox.classList.add('hidden');
                    weightWarning.classList.add('hidden');
                    driverWarning.classList.add('hidden');
                    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
                    document.querySelectorAll('.error-msg').forEach(el => el.classList.add('hidden'));
                    checkFormValidity();
                }, 10);
            });

            // Prevent default submission for demo. Remove or modify this for real PHP submission.
            form.addEventListener('submit', (e) => {
                // To actually let PHP handle this, remove e.preventDefault(); 
                // e.preventDefault(); 
                
                if(!btnSubmit.disabled) {
                    const originalBtnContent = btnSubmit.innerHTML;
                    btnSubmit.innerHTML = `<i class="ph ph-spinner animate-spin text-lg"></i> Dispatching...`;
                    
                    // Demo Mode Success Simulation (remove if actual PHP POST is intended immediately)
                    setTimeout(() => {
                        alert("Success: Trip created and dispatched successfully. System updated.");
                        form.reset();
                        resetBtn.click();
                        btnSubmit.innerHTML = originalBtnContent;
                    }, 1500);
                }
            });
        });
    </script>
</body>
</html>