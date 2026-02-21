<?php
// ================= BACKEND PLACEHOLDER =================

// 1. Verify manager session
// 2. Fetch vehicle list
// 3. Fetch maintenance records
// 4. Handle maintenance form submission
// 5. Insert maintenance record
// 6. Update vehicle status to 'in_shop'
// 7. Handle mark completed
// 8. Update vehicle status to 'available'
// 9. Calculate service due alerts

// =======================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Fleet Maintenance</title>
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
        
        /* For dark calendar picker icon */
        ::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
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
            
            <a href="manager_trip_dispatcher.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-map-pin-line text-xl"></i>
                <span class="font-medium">Trip Dispatcher</span>
            </a>
            
            <!-- ACTIVE STATE: Maintenance -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand-gold/10 text-brand-gold border border-brand-gold/20 transition-all flex-wrap relative">
                <i class="ph-fill ph-wrench text-xl"></i>
                <span class="font-medium">Maintenance</span>
                <span class="absolute right-4 w-2 h-2 rounded-full bg-status-danger animate-pulse"></span>
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
                <h2 class="text-2xl font-semibold text-white">Fleet Maintenance</h2>
                <p class="text-sm text-gray-400 mt-1">Monitor vehicle health and manage service operations.</p>
            </div>

            <!-- Maintenance Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <!-- Card 1 -->
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-status-danger/10 flex items-center justify-center border border-status-danger/20 text-status-danger group-hover:bg-status-danger/20 transition-colors">
                        <i class="ph-fill ph-wrench text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white leading-tight">12</h3>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">In Maintenance</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-status-warning/10 flex items-center justify-center border border-status-warning/20 text-status-warning group-hover:bg-status-warning/20 transition-colors">
                        <i class="ph-fill ph-warning-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white leading-tight">5</h3>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Service Due Soon</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-status-success/10 flex items-center justify-center border border-status-success/20 text-status-success group-hover:bg-status-success/20 transition-colors">
                        <i class="ph-fill ph-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white leading-tight">8</h3>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Completed (Today)</p>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-brand-gold/10 flex items-center justify-center border border-brand-gold/20 text-brand-gold group-hover:bg-brand-gold/20 transition-colors">
                        <i class="ph-fill ph-currency-circle-dollar text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white leading-tight">₹ 45.2k</h3>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total Est. Cost</p>
                    </div>
                </div>
            </div>

            <!-- Send Vehicle to Maintenance Form -->
            <div class="glass-panel rounded-3xl p-8 mb-8 border-white/5 relative overflow-visible">
                <div class="absolute inset-0 bg-gradient-to-br from-status-danger/5 via-transparent to-transparent pointer-events-none rounded-3xl"></div>
                
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-status-danger/10 flex items-center justify-center border border-status-danger/20 shadow-[0_0_15px_rgba(239,68,68,0.15)]">
                            <i class="ph-fill ph-stethoscope text-2xl text-status-danger"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">Send to Maintenance</h3>
                            <p class="text-sm text-gray-400 mt-0.5">Schedule a service or repair for an operational vehicle</p>
                        </div>
                    </div>
                </div>

                <?php
                // TODO: Save maintenance record
                // TODO: Update vehicle status to 'in_shop'
                ?>
                <form id="maintenanceForm" method="POST" action="" class="relative z-10" novalidate>
                    <!-- 2-Column Responsive Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        
                        <!-- LEFT COLUMN: Vehicle Details -->
                        <div class="space-y-6">
                            <h4 class="text-sm font-medium text-brand-gold uppercase tracking-widest mb-4"><i class="ph-fill ph-car-profile mr-1"></i> Vehicle Info</h4>
                            
                            <!-- Select Vehicle -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="mVehicle">Select Vehicle</label>
                                <div class="relative">
                                    <i class="ph ph-truck absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10 group-focus-within:text-brand-gold transition-colors"></i>
                                    <select id="mVehicle" name="vehicle_id" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                        <option value="" disabled selected>Choose a vehicle</option>
                                        <option value="v1" data-plate="XYZ-7741">Volvo VNL 860 (Truck)</option>
                                        <option value="v2" data-plate="DEF-9012">Ford Transit 250 (Van)</option>
                                        <option value="v3" data-plate="RET-0099">Honda Activa 6G (Bike)</option>
                                    </select>
                                </div>
                                <p class="text-status-danger text-[11px] hidden error-msg">Vehicle selection is required.</p>
                            </div>

                            <!-- Vehicle Plate (Auto-filled) -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="mPlate">Vehicle Plate</label>
                                <div class="relative">
                                    <i class="ph ph-identification-badge absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 group-focus-within:text-brand-gold transition-colors"></i>
                                    <input type="text" id="mPlate" name="license_plate" placeholder="Auto-filled" class="form-input pl-10 bg-black/40 text-gray-400 cursor-not-allowed border-white/5 font-mono" readonly>
                                </div>
                            </div>

                            <!-- Service Type -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="mServiceType">Service Type</label>
                                <div class="relative">
                                    <i class="ph ph-list-dashes absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10 group-focus-within:text-brand-gold transition-colors"></i>
                                    <select id="mServiceType" name="service_type" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                        <option value="" disabled selected>Select service category</option>
                                        <option value="Oil Change">Oil Change</option>
                                        <option value="Engine Repair">Engine Repair</option>
                                        <option value="Tire Replacement">Tire Replacement</option>
                                        <option value="General Service">General Service</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <p class="text-status-danger text-[11px] hidden error-msg">Service type is required.</p>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Timing & Costs -->
                        <div class="space-y-6">
                            <h4 class="text-sm font-medium text-brand-gold uppercase tracking-widest mb-4"><i class="ph-fill ph-calendar-blank mr-1"></i> Logistics & Cost</h4>

                            <!-- Service Date -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="mDate">Service Date</label>
                                <div class="relative">
                                    <i class="ph ph-calendar-check absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10 group-focus-within:text-brand-gold transition-colors"></i>
                                    <input type="date" id="mDate" name="service_date" class="form-input pl-10" required>
                                </div>
                                <p id="dateWarning" class="text-status-danger text-[11px] hidden error-msg">Date cannot be in the past.</p>
                            </div>

                            <!-- Estimated Cost -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="mCost">Estimated Cost (₹)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">₹</span>
                                    <input type="number" id="mCost" name="estimated_cost" placeholder="e.g. 5000" min="1" step="0.01" class="form-input pl-8" required>
                                </div>
                                <p class="text-status-danger text-[11px] hidden error-msg">Valid cost is required.</p>
                            </div>

                            <!-- Notes / Description -->
                            <div class="space-y-2 relative group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider" for="mNotes">Notes / Description</label>
                                <div class="relative">
                                    <i class="ph ph-text-align-left absolute left-3 top-3 text-gray-500 group-focus-within:text-brand-gold transition-colors"></i>
                                    <textarea id="mNotes" name="description" placeholder="Describe the issue or service needed..." class="form-input pl-10 h-20 resize-none" required></textarea>
                                </div>
                                <p class="text-status-danger text-[11px] hidden error-msg">Description is required.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Area -->
                    <div class="pt-6 mt-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <div id="formStatusIcon" class="w-2 h-2 rounded-full bg-status-danger animate-pulse"></div>
                            <span id="formStatusText">Pending details...</span>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <button type="reset" id="resetBtn" class="btn-outline px-6 py-3 rounded-xl text-sm font-medium w-full sm:w-auto flex items-center justify-center gap-2">
                                <i class="ph ph-arrow-counter-clockwise"></i> Reset
                            </button>
                            <!-- Send to Maintenance Button -->
                            <button type="submit" name="send_maintenance" id="submitMaintenanceBtn" class="btn-premium bg-gradient-to-r from-status-danger to-red-600 hover:shadow-[0_0_15px_rgba(239,68,68,0.4)] px-8 py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2 whitespace-nowrap w-full sm:w-auto text-white border-none" disabled>
                                <i class="ph-bold ph-wrench text-lg"></i> Dispatch to Shop
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Maintenance Records Table -->
            <div class="mb-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-status-info/10 flex items-center justify-center border border-status-info/20">
                    <i class="ph-fill ph-clipboard-text text-status-info"></i>
                </div>
                <h3 class="text-lg font-semibold text-white">Maintenance Records</h3>
            </div>
            
            <?php
            // TODO: Fetch maintenance records
            // TODO: Calculate service due based on last service or mileage
            // TODO: Handle mark completed action
            ?>
            <div class="glass-panel rounded-3xl overflow-hidden flex-1 flex flex-col min-h-[300px]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white/5 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-white/10">
                                <th class="py-4 pl-6">Record ID</th>
                                <th class="py-4 px-4">Vehicle Details</th>
                                <th class="py-4 px-4">Service Type</th>
                                <th class="py-4 px-4">Service Date</th>
                                <th class="py-4 px-4 text-right">Cost (₹)</th>
                                <th class="py-4 px-4 text-center">Status</th>
                                <th class="py-4 pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white/5">
                            
                            <!-- In Progress Row -->
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-6 font-medium text-white">#MN-4029</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        <p class="text-gray-200 font-medium">Volvo VNL 860</p>
                                        <?php // HACKATHON FEATURE: Service Due Alert Indicator ?>
                                        <span class="inline-flex items-center gap-1 bg-status-warning/20 text-status-warning text-[9px] px-1.5 py-0.5 rounded uppercase font-bold tracking-wider" title="Odometer exceeded threshold">
                                            <i class="ph-fill ph-warning"></i> Due
                                        </span>
                                    </div>
                                    <p class="font-mono text-xs text-gray-500 mt-0.5">XYZ-7741</p>
                                </td>
                                <td class="py-4 px-4 text-gray-300">Engine Repair</td>
                                <td class="py-4 px-4 text-gray-300">Feb 21, 2026</td>
                                <td class="py-4 px-4 text-right text-gray-300 font-medium">12,500.00</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-status-warning/10 text-status-warning border border-status-warning/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-status-warning mr-1.5 animate-pulse"></span> In Progress
                                    </span>
                                </td>
                                <td class="py-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-status-success hover:text-white flex items-center justify-center text-gray-400 transition-colors" title="Mark Completed">
                                            <i class="ph ph-check-circle text-lg"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-brand-gold hover:text-black flex items-center justify-center text-gray-400 transition-colors" title="View Details">
                                            <i class="ph ph-eye text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Completed Row -->
                            <tr class="hover:bg-white/5 transition-colors group opacity-80">
                                <td class="py-4 pl-6 font-medium text-white">#MN-4028</td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-200 font-medium">Ford Transit 250</p>
                                    <p class="font-mono text-xs text-gray-500 mt-0.5">DEF-9012</p>
                                </td>
                                <td class="py-4 px-4 text-gray-300">Oil Change</td>
                                <td class="py-4 px-4 text-gray-300">Feb 18, 2026</td>
                                <td class="py-4 px-4 text-right text-gray-300 font-medium">2,200.00</td>
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
                            
                            <!-- Completed Row 2 -->
                            <tr class="hover:bg-white/5 transition-colors group opacity-80">
                                <td class="py-4 pl-6 font-medium text-white">#MN-4022</td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-200 font-medium">Honda Activa 6G</p>
                                    <p class="font-mono text-xs text-gray-500 mt-0.5">RET-0099</p>
                                </td>
                                <td class="py-4 px-4 text-gray-300">Tire Replacement</td>
                                <td class="py-4 px-4 text-gray-300">Jan 12, 2026</td>
                                <td class="py-4 px-4 text-right text-gray-300 font-medium">1,800.00</td>
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

    <!-- Frontend Form Validation & Interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            const form = document.getElementById('maintenanceForm');
            const btnSubmit = document.getElementById('submitMaintenanceBtn');
            const resetBtn = document.getElementById('resetBtn');
            
            const inputs = {
                vehicle: document.getElementById('mVehicle'),
                plate: document.getElementById('mPlate'),
                serviceType: document.getElementById('mServiceType'),
                date: document.getElementById('mDate'),
                cost: document.getElementById('mCost'),
                notes: document.getElementById('mNotes')
            };

            // UI Elements
            const dateWarning = document.getElementById('dateWarning');
            const statusIcon = document.getElementById('formStatusIcon');
            const statusText = document.getElementById('formStatusText');

            // Auto-fill plate based on vehicle selection
            inputs.vehicle.addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const plate = selectedOpt.getAttribute('data-plate');
                inputs.plate.value = plate ? plate : '';
            });

            // Set min date for date picker to today
            const today = new Date().toISOString().split('T')[0];
            inputs.date.setAttribute('min', today);

            // Validations
            const validators = {
                vehicle: (val) => val !== "",
                plate: (val) => true, // Auto-filled, inherently valid
                serviceType: (val) => val !== "",
                date: (val) => {
                    if (!val) return false;
                    const selectedDate = new Date(val);
                    const todayDate = new Date();
                    todayDate.setHours(0,0,0,0);
                    
                    if(selectedDate < todayDate) {
                        inputs.date.classList.add('input-error');
                        dateWarning.classList.remove('hidden');
                        return false;
                    } else {
                        inputs.date.classList.remove('input-error');
                        dateWarning.classList.add('hidden');
                        return true;
                    }
                },
                cost: (val) => !isNaN(val) && parseFloat(val) > 0,
                notes: (val) => val.trim().length >= 5
            };

            function validateField(inputElement, fieldKey) {
                if(fieldKey === 'date') {
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
                    statusText.textContent = "Ready to dispatch to shop";
                    statusText.classList.replace('text-gray-500', 'text-status-success');
                    
                    // Button styling change when active
                    btnSubmit.classList.remove('bg-status-danger', 'bg-gradient-to-r', 'from-status-danger', 'to-red-600', 'text-white', 'border-none');
                    btnSubmit.classList.add('btn-premium');
                } else {
                    statusIcon.classList.replace('bg-status-success', 'bg-status-danger');
                    statusIcon.classList.add('animate-pulse');
                    statusText.textContent = "Pending details...";
                    statusText.classList.replace('text-status-success', 'text-gray-500');
                    
                    // Button styling revert
                    btnSubmit.classList.add('bg-gradient-to-r', 'from-status-danger', 'to-red-600', 'text-white', 'border-none');
                    btnSubmit.classList.remove('btn-premium');
                }
            }

            // General listeners
            for (const key in inputs) {
                const checkAndUpdate = (e) => {
                    validateField(e.target, key);
                    checkFormValidity();
                };
                inputs[key].addEventListener('input', checkAndUpdate);
                inputs[key].addEventListener('change', checkAndUpdate);
                inputs[key].addEventListener('blur', checkAndUpdate);
            }

            // Reset behavior
            resetBtn.addEventListener('click', () => {
                setTimeout(() => {
                    dateWarning.classList.add('hidden');
                    inputs.plate.value = '';
                    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
                    document.querySelectorAll('.error-msg').forEach(el => el.classList.add('hidden'));
                    checkFormValidity();
                }, 10);
            });

            // Prevent default submission for demo
            form.addEventListener('submit', (e) => {
                // To actually let PHP handle this, remove e.preventDefault(); 
                // e.preventDefault(); 
                
                if(!btnSubmit.disabled) {
                    const originalBtnContent = btnSubmit.innerHTML;
                    btnSubmit.innerHTML = `<i class="ph ph-spinner animate-spin text-lg"></i> Processing...`;
                    
                    // Demo Mode Success Simulation
                    setTimeout(() => {
                        alert("Success: Vehicle moved to maintenance and records updated.");
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