<?php
// ================= BACKEND PLACEHOLDER =================
// 1. Verify manager session and role
// 2. Fetch all drivers from database
// 3. Handle Add Driver form submission
// 4. Validate license expiry date
// 5. Calculate days remaining for license
// 6. Update driver status (on_duty / off_duty / suspended)
// 7. Filter drivers by status
// 8. Handle edit and update operations
// =======================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Driver Management</title>
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
        #addDriverFormContainer {
            transition: max-height 0.4s ease-in-out, opacity 0.4s ease-in-out, padding 0.4s ease-in-out;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            padding-top: 0;
            padding-bottom: 0;
            border-width: 0;
        }
        #addDriverFormContainer.expanded {
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
            
            <!-- ACTIVE STATE: Drivers -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand-gold/10 text-brand-gold border border-brand-gold/20 transition-all">
                <i class="ph-fill ph-users text-xl"></i>
                <span class="font-medium">Drivers</span>
            </a>

            <a href="manager_register.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-user-plus text-xl"></i>
                <span class="font-medium">Registration</span>
            </a>
            
            <a href="dispatcher.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-map-pin-line text-xl"></i>
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
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-semibold text-white">Driver Management</h2>
                    <p class="text-sm text-gray-400 mt-1">Manage driver availability, compliance, and operational status.</p>
                </div>
                
                <!-- Action Bar -->
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <div class="relative">
                        <i class="ph ph-funnel absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                        <select class="form-input pl-9 py-2 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center] min-w-[160px]">
                            <option value="all">Filter: All Status</option>
                            <option value="on_duty">On Duty</option>
                            <option value="off_duty">Off Duty</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div class="relative flex-1 md:w-64">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                        <input type="text" placeholder="Search name or license..." class="form-input pl-9 py-2">
                    </div>
                    <button id="toggleFormBtn" class="btn-premium px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
                        <i class="ph-bold ph-plus"></i> Add New Driver
                    </button>
                </div>
            </div>

            <!-- Add Driver Form (Expandable Panel) -->
            <div id="addDriverFormContainer" class="glass-panel rounded-3xl px-8 mb-8 border-white/5 relative">
                <button id="closeFormBtn" class="absolute top-4 right-6 text-gray-500 hover:text-white transition-colors">
                    <i class="ph ph-x text-xl"></i>
                </button>
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/5">
                    <div class="w-10 h-10 rounded-xl bg-brand-gold/10 flex items-center justify-center border border-brand-gold/20">
                        <i class="ph-fill ph-identification-card text-xl text-brand-gold"></i>
                    </div>
                    <h3 class="text-lg font-medium text-white">Register New Driver</h3>
                </div>

                <form id="driverForm" class="space-y-6" novalidate>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Driver Name -->
                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Driver Name</label>
                            <div class="relative">
                                <i class="ph ph-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                <input type="text" id="dName" placeholder="e.g. Robert Jensen" class="form-input pl-10" required>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Driver name is required.</p>
                        </div>

                        <!-- Phone Number -->
                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Phone Number</label>
                            <div class="relative">
                                <i class="ph ph-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                <input type="tel" id="dPhone" placeholder="Enter 10-digit number" class="form-input pl-10" required>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Must be exactly 10 digits.</p>
                        </div>

                        <!-- License Number -->
                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">License Number</label>
                            <div class="relative">
                                <i class="ph ph-address-book absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                <input type="text" id="dLicense" placeholder="e.g. DL-123456789" class="form-input pl-10 uppercase" required>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Valid license format required.</p>
                        </div>

                        <!-- License Expiry Date -->
                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">License Expiry Date</label>
                            <div class="relative">
                                <i class="ph ph-calendar-blank absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                <input type="date" id="dExpiry" class="form-input pl-10" required>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Expiry must be a future date.</p>
                        </div>

                        <!-- Allowed Vehicle Type -->
                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Vehicle Type Allowed</label>
                            <div class="relative">
                                <i class="ph ph-steering-wheel absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                                <select id="dType" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                    <option value="" disabled selected>Select vehicle clearance</option>
                                    <option value="Truck">Heavy Truck</option>
                                    <option value="Van">Cargo Van</option>
                                    <option value="Bike">Delivery Bike</option>
                                    <option value="All">All Vehicles</option>
                                </select>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Select vehicle clearance.</p>
                        </div>

                        <!-- Initial Status -->
                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Initial Status</label>
                            <div class="relative">
                                <i class="ph ph-toggle-left absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                                <select id="dStatus" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                    <option value="" disabled selected>Select initial status</option>
                                    <option value="On Duty">On Duty</option>
                                    <option value="Off Duty">Off Duty</option>
                                    <option value="Suspended">Suspended</option>
                                </select>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Please assign an initial status.</p>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-6 mt-2 border-t border-white/5 flex items-center justify-end">
                        <button type="submit" id="submitDriverBtn" class="btn-premium px-8 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2" disabled>
                            <i class="ph-bold ph-plus"></i> Add Driver
                        </button>
                    </div>
                </form>
            </div>

            <!-- Driver List Table Area -->
            <div class="glass-panel rounded-3xl overflow-hidden flex-1 flex flex-col min-h-[400px]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white/5 text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-white/10">
                                <th class="py-4 pl-6">Driver Info</th>
                                <th class="py-4 px-4">License / Phone</th>
                                <th class="py-4 px-4 text-center">Compliance (Expiry)</th>
                                <th class="py-4 px-4">Clearance</th>
                                <th class="py-4 px-4 text-center">Status</th>
                                <th class="py-4 pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white/5">
                            
                            <!-- Valid & On Duty -->
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-brand-gold/20 flex items-center justify-center text-brand-gold font-bold">
                                            AT
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">Alex Thompson</p>
                                            <p class="text-[11px] text-gray-500">ID: #DRV-8492</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-mono text-gray-200">DL-2200-3491</p>
                                    <p class="text-xs text-gray-400">+1 (555) 019-2034</p>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-status-success/10 text-status-success border border-status-success/20">
                                            <i class="ph-fill ph-check-circle"></i> Valid (312 days)
                                        </span>
                                        <span class="text-[10px] text-gray-500 mt-1">Exp: Oct 12, 2027</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 text-gray-300 bg-white/5 px-2 py-1 rounded text-xs border border-white/10">
                                        <i class="ph-fill ph-van text-brand-gold"></i> Van
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-status-success/10 text-status-success border border-status-success/20">
                                        On Duty
                                    </span>
                                </td>
                                <td class="py-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-brand-gold hover:text-black flex items-center justify-center text-gray-400 transition-colors" title="Edit Driver">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-status-gray hover:text-white flex items-center justify-center text-gray-400 transition-colors" title="Mark Off Duty">
                                            <i class="ph ph-coffee text-lg"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-status-danger hover:text-white flex items-center justify-center text-gray-400 transition-colors" title="Suspend Driver">
                                            <i class="ph ph-prohibit text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Expiring Soon & Off Duty -->
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-gray-300 font-bold border border-white/10">
                                            MR
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">Marcus Reed</p>
                                            <p class="text-[11px] text-gray-500">ID: #DRV-8311</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-mono text-gray-200">DL-4411-9022</p>
                                    <p class="text-xs text-gray-400">+1 (555) 882-1044</p>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-status-warning/10 text-status-warning border border-status-warning/20">
                                            <i class="ph-fill ph-warning-circle"></i> Expiring Soon (12 days)
                                        </span>
                                        <span class="text-[10px] text-gray-500 mt-1">Exp: Sep 14, 2026</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 text-gray-300 bg-white/5 px-2 py-1 rounded text-xs border border-white/10">
                                        <i class="ph-fill ph-truck text-status-info"></i> Truck
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-white/5 text-gray-400 border border-white/10">
                                        Off Duty
                                    </span>
                                </td>
                                <td class="py-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-brand-gold hover:text-black flex items-center justify-center text-gray-400 transition-colors" title="Edit Driver">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-status-success hover:text-white flex items-center justify-center text-gray-400 transition-colors" title="Mark On Duty">
                                            <i class="ph ph-play-circle text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Expired & Suspended -->
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-status-danger/20 flex items-center justify-center text-status-danger font-bold border border-status-danger/30">
                                            SJ
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">Sarah Jenkins</p>
                                            <p class="text-[11px] text-gray-500">ID: #DRV-7705</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-mono text-gray-200">DL-1100-8833</p>
                                    <p class="text-xs text-gray-400">+1 (555) 433-2190</p>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-status-danger/10 text-status-danger border border-status-danger/20 animate-pulse">
                                            <i class="ph-fill ph-x-circle"></i> Expired (5 days ago)
                                        </span>
                                        <span class="text-[10px] text-status-danger mt-1">Exp: Aug 28, 2026</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 text-gray-300 bg-white/5 px-2 py-1 rounded text-xs border border-white/10">
                                        <i class="ph-fill ph-motorcycle text-gray-400"></i> Bike
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-status-danger/10 text-status-danger border border-status-danger/20">
                                        Suspended
                                    </span>
                                </td>
                                <td class="py-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-brand-gold hover:text-black flex items-center justify-center text-gray-400 transition-colors" title="Update License">
                                            <i class="ph ph-identification-card text-lg"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white hover:text-black flex items-center justify-center text-gray-400 transition-colors" title="Lift Suspension">
                                            <i class="ph ph-arrow-counter-clockwise text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Footer -->
                <div class="mt-auto px-6 py-4 border-t border-white/5 flex items-center justify-between">
                    <p class="text-xs text-gray-500">Showing 1 to 3 of 48 drivers</p>
                    <div class="flex items-center gap-2">
                        <button class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors"><i class="ph ph-caret-left"></i></button>
                        <button class="w-8 h-8 rounded-lg bg-brand-gold/20 flex items-center justify-center text-brand-gold font-medium text-sm">1</button>
                        <button class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors text-sm">2</button>
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
            
            // Toggle Add Driver Form
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const closeFormBtn = document.getElementById('closeFormBtn');
            const formContainer = document.getElementById('addDriverFormContainer');
            
            toggleFormBtn.addEventListener('click', () => {
                formContainer.classList.add('expanded');
                document.getElementById('dName').focus();
            });
            
            closeFormBtn.addEventListener('click', () => {
                formContainer.classList.remove('expanded');
            });

            // Form Validation Logic
            const form = document.getElementById('driverForm');
            const btnSubmit = document.getElementById('submitDriverBtn');
            
            const inputs = {
                name: document.getElementById('dName'),
                phone: document.getElementById('dPhone'),
                license: document.getElementById('dLicense'),
                expiry: document.getElementById('dExpiry'),
                type: document.getElementById('dType'),
                status: document.getElementById('dStatus')
            };

            const validators = {
                name: (val) => val.trim().length >= 3,
                phone: (val) => /^\d{10}$/.test(val.trim()), // Exactly 10 digits
                license: (val) => /^[A-Z0-9-]{5,15}$/i.test(val.trim()), // Basic alphanumeric with dashes
                expiry: (val) => {
                    if(!val) return false;
                    const selectedDate = new Date(val);
                    const today = new Date();
                    today.setHours(0,0,0,0);
                    return selectedDate > today; // Must be a future date
                },
                type: (val) => val !== "",
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
                    if(key === 'license') {
                        e.target.value = e.target.value.toUpperCase(); // Force uppercase for license
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
                    
                    // Mock Success Simulation
                    setTimeout(() => {
                        alert("Success: Driver successfully registered to OptiFleet.");
                        form.reset();
                        checkFormValidity();
                        formContainer.classList.remove('expanded');
                        btnSubmit.innerHTML = `<i class="ph-bold ph-plus"></i> Add Driver`;
                    }, 1200);
                }
            });
        });
    </script>
</body>
</html>