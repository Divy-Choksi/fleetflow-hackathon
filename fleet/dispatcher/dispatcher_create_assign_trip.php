<?php
// ================= BACKEND PLACEHOLDER =================

// 1. Verify dispatcher session
// 2. Fetch available vehicles (status = available, ordered by capacity)
// 3. Fetch available drivers (status = on_duty, check license expiry)
// 4. Handle POST request for trip creation
// 5. Generate unique Trip ID
// 6. Insert trip data, route data, cargo data
// 7. Update vehicle & driver status to 'assigned' / 'on_trip'

// =======================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Create & Assign Trip</title>
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
        .form-input:disabled, .form-input[readonly] { background-color: rgba(0,0,0,0.4); color: #9ca3af; cursor: not-allowed; }
        
        /* Custom Select Options */
        select.form-input option { background-color: #0A0A0B; color: white; }

        /* Checkbox Custom Styling */
        .custom-checkbox {
            appearance: none;
            background-color: rgba(255, 255, 255, 0.05);
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 1.15em;
            height: 1.15em;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.25em;
            display: grid;
            place-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .custom-checkbox::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em #D4AF37;
            background-color: #D4AF37;
            transform-origin: center;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .custom-checkbox:checked::before { transform: scale(1); }
        .custom-checkbox:checked { border-color: #D4AF37; }
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
            <a href="dispatcher_dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-squares-four text-xl"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <!-- ACTIVE STATE: Create & Assign Trip -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand-gold/10 text-brand-gold border border-brand-gold/20 transition-all">
                <i class="ph-fill ph-paper-plane-tilt text-xl"></i>
                <span class="font-medium">Create & Assign Trip</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-broadcast text-xl"></i>
                <span class="font-medium">Live Dispatch Tracking</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-map-pin-line text-xl"></i>
                <span class="font-medium">Trip Management</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-truck text-xl"></i>
                <span class="font-medium">Vehicle Availability</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-users text-xl"></i>
                <span class="font-medium">Driver Availability</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-gas-pump text-xl"></i>
                <span class="font-medium">Fuel & Expense Entry</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all flex-wrap relative">
                <i class="ph ph-bell text-xl"></i>
                <span class="font-medium">Notifications</span>
                <span class="absolute right-4 w-2 h-2 rounded-full bg-status-danger"></span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-chart-line-up text-xl"></i>
                <span class="font-medium">Reports</span>
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

        <!-- Form Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-8 flex flex-col">
            
            <!-- Page Header & Breadcrumbs -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2 font-medium tracking-wide">
                        <a href="dispatcher_dashboard.php" class="hover:text-brand-gold transition-colors">Dashboard</a>
                        <i class="ph ph-caret-right text-[10px]"></i>
                        <span class="text-gray-300">Create Trip</span>
                    </div>
                    <h2 class="text-3xl font-semibold text-white">Create & Assign Trip</h2>
                    <p class="text-sm text-gray-400 mt-1">Schedule and dispatch a new trip with intelligent resource validation.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="dispatcher_dashboard.php" class="btn-outline px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i class="ph ph-arrow-left text-lg"></i> Back
                    </a>
                </div>
            </div>

            <!-- Form Wrapper -->
            <form id="createTripForm" method="POST" action="" class="space-y-6" novalidate>
                
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <!-- LEFT COLUMN (Spans 2/3): Core Trip Details -->
                    <div class="xl:col-span-2 space-y-6">
                        
                        <!-- SECTION 1: Trip Details -->
                        <div class="glass-panel rounded-3xl p-7 border-white/5 relative overflow-hidden group">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/5">
                                <div class="w-10 h-10 rounded-xl bg-brand-gold/10 flex items-center justify-center border border-brand-gold/20 text-brand-gold">
                                    <i class="ph-fill ph-calendar-plus text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-white">1. Trip Details</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Trip ID -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Trip ID</label>
                                    <div class="relative">
                                        <i class="ph ph-hash absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                        <input type="text" value="#TRP-8509" class="form-input pl-10 font-mono tracking-wide" readonly>
                                    </div>
                                </div>
                                <!-- Dispatch Date & Time -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Dispatch Schedule</label>
                                    <div class="relative">
                                        <i class="ph ph-calendar-blank absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                                        <input type="datetime-local" class="form-input pl-10 custom-calendar" required>
                                    </div>
                                </div>
                                <!-- Priority -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Priority Level</label>
                                    <div class="relative">
                                        <i class="ph ph-siren absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                                        <select class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]">
                                            <option value="Normal" selected>Normal</option>
                                            <option value="High">High</option>
                                            <option value="Urgent">Urgent</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Fleet Type Required -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Fleet Type Required</label>
                                    <div class="relative">
                                        <i class="ph ph-truck absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                                        <select id="reqFleetType" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                            <option value="" disabled selected>Select optimal type</option>
                                            <option value="Truck">Heavy Truck</option>
                                            <option value="Van">Cargo Van</option>
                                            <option value="Bike">Delivery Bike</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Route Information -->
                        <div class="glass-panel rounded-3xl p-7 border-white/5 relative overflow-hidden">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/5">
                                <div class="w-10 h-10 rounded-xl bg-status-info/10 flex items-center justify-center border border-status-info/20 text-status-info">
                                    <i class="ph-fill ph-map-pin-line text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-white">2. Route Information</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">
                                <!-- Origin -->
                                <div class="space-y-2 relative z-10">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Origin Location</label>
                                    <div class="relative">
                                        <i class="ph-fill ph-record absolute left-3 top-1/2 transform -translate-y-1/2 text-status-info"></i>
                                        <input type="text" placeholder="Pickup address or Hub ID" class="form-input pl-10" required>
                                    </div>
                                </div>
                                <!-- Destination -->
                                <div class="space-y-2 relative z-10">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Destination Location</label>
                                    <div class="relative">
                                        <i class="ph-fill ph-map-pin absolute left-3 top-1/2 transform -translate-y-1/2 text-status-danger"></i>
                                        <input type="text" placeholder="Drop-off address or Zone" class="form-input pl-10" required>
                                    </div>
                                </div>
                                
                                <!-- Route visual connector (Desktop only) -->
                                <div class="hidden md:block absolute top-[45px] left-1/2 w-8 h-0.5 bg-gray-600/50 -translate-x-1/2 z-0 flex items-center justify-center">
                                    <i class="ph-bold ph-arrow-right text-gray-500 absolute bg-[#0a0a0a] px-1"></i>
                                </div>

                                <!-- Est Distance -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Estimated Distance (km)</label>
                                    <div class="relative">
                                        <i class="ph ph-navigation-arrow absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                        <input type="number" id="routeDistance" placeholder="0" class="form-input pl-10" min="1" required>
                                    </div>
                                </div>
                                <!-- Est Duration -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Estimated Duration (hrs)</label>
                                    <div class="relative">
                                        <i class="ph ph-clock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                        <input type="number" placeholder="0.0" step="0.5" class="form-input pl-10" min="0.5" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Cargo Information -->
                        <div class="glass-panel rounded-3xl p-7 border-white/5 relative overflow-hidden">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/5">
                                <div class="w-10 h-10 rounded-xl bg-status-warning/10 flex items-center justify-center border border-status-warning/20 text-status-warning">
                                    <i class="ph-fill ph-package text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-white">3. Cargo Information</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Cargo Type -->
                                <div class="space-y-2 md:col-span-1">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Cargo Type</label>
                                    <div class="relative">
                                        <i class="ph ph-boxes absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                                        <select class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                            <option value="" disabled selected>Select category</option>
                                            <option value="Electronics">Electronics</option>
                                            <option value="Furniture">Furniture</option>
                                            <option value="Food/Beverage">Food & Beverage</option>
                                            <option value="Documents">Documents/Parcels</option>
                                            <option value="Industrial">Industrial Goods</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Cargo Weight -->
                                <div class="space-y-2 md:col-span-1">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Total Weight (kg)</label>
                                    <div class="relative">
                                        <i class="ph ph-scales absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                        <input type="number" id="cargoWeight" placeholder="0" class="form-input pl-10" min="1" required>
                                    </div>
                                </div>
                                <!-- Total Units -->
                                <div class="space-y-2 md:col-span-1">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Number of Units</label>
                                    <div class="relative">
                                        <i class="ph ph-stack absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                        <input type="number" placeholder="0" class="form-input pl-10" min="1">
                                    </div>
                                </div>

                                <!-- Intelligent Weight Warning Box -->
                                <div id="weightWarningBox" class="hidden md:col-span-3 bg-status-danger/10 border border-status-danger/30 rounded-xl p-3 flex items-center gap-3">
                                    <i class="ph-fill ph-warning-circle text-status-danger text-xl"></i>
                                    <div>
                                        <p class="text-sm font-semibold text-status-danger">Excessive Cargo Weight Detected</p>
                                        <p class="text-xs text-red-200 mt-0.5">Cargo weight exceeds the maximum capacity of currently available vehicles (2500 kg).</p>
                                    </div>
                                </div>

                                <!-- Special Handling Checkboxes -->
                                <div class="md:col-span-3 space-y-3">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Special Handling Requirements</label>
                                    <div class="flex flex-wrap gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" class="custom-checkbox">
                                            <span class="text-sm text-gray-300 group-hover:text-white transition-colors flex items-center gap-1.5"><i class="ph ph-wine text-gray-500"></i> Fragile</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" class="custom-checkbox">
                                            <span class="text-sm text-gray-300 group-hover:text-white transition-colors flex items-center gap-1.5"><i class="ph ph-thermometer-cold text-gray-500"></i> Perishable</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" class="custom-checkbox">
                                            <span class="text-sm text-gray-300 group-hover:text-white transition-colors flex items-center gap-1.5"><i class="ph ph-warning-octagon text-gray-500"></i> Hazardous</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" class="custom-checkbox">
                                            <span class="text-sm text-gray-300 group-hover:text-white transition-colors flex items-center gap-1.5"><i class="ph ph-lock-key text-gray-500"></i> High Value</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN (Spans 1/3): Assignment & Economics -->
                    <div class="xl:col-span-1 space-y-6">
                        
                        <!-- SECTION 4: Vehicle Assignment -->
                        <div class="glass-panel rounded-3xl p-6 border-white/5 relative overflow-hidden">
                            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-white/5">
                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center border border-white/20 text-white">
                                    <i class="ph-fill ph-truck text-lg"></i>
                                </div>
                                <h3 class="text-base font-medium text-white">4. Vehicle Assignment</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Select Available Asset</label>
                                    <div class="relative">
                                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                                        <select id="vehicleAssign" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                            <option value="" disabled selected>Search fleet...</option>
                                            <option value="v1" data-capacity="2500" data-status="Available">Volvo VNL (Heavy) – 2500kg</option>
                                            <option value="v2" data-capacity="800" data-status="Available">Transit 250 (Van) – 800kg</option>
                                            <option value="v3" data-capacity="50" data-status="Available">Activa 6G (Bike) – 50kg</option>
                                            <option value="v4" data-capacity="1200" data-status="Maintenance" disabled>Sprinter (Van) – Maintenance</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Vehicle Validation Messages -->
                                <div id="vehicleMatchBox" class="hidden rounded-xl p-3 flex items-start gap-2 text-sm">
                                    <!-- Populated via JS -->
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 5: Driver Assignment -->
                        <div class="glass-panel rounded-3xl p-6 border-white/5 relative overflow-hidden">
                            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-white/5">
                                <div class="w-8 h-8 rounded-lg bg-brand-gold/10 flex items-center justify-center border border-brand-gold/20 text-brand-gold">
                                    <i class="ph-fill ph-steering-wheel text-lg"></i>
                                </div>
                                <h3 class="text-base font-medium text-white">5. Driver Assignment</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Select On-Duty Driver</label>
                                    <div class="relative">
                                        <i class="ph ph-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                                        <select id="driverAssign" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                            <option value="" disabled selected>Search drivers...</option>
                                            <option value="d1" data-license="Heavy" data-status="Valid">Alex Thompson (Heavy)</option>
                                            <option value="d2" data-license="Bike" data-status="Expired">Sarah Jenkins (Bike) - Expired</option>
                                            <option value="d3" data-license="Van" data-status="On Trip" disabled>David Chen (Van) - On Trip</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Driver Validation Messages -->
                                <div id="driverMatchBox" class="hidden rounded-xl p-3 flex items-start gap-2 text-sm border border-status-danger/30 bg-status-danger/10 text-status-danger">
                                    <i class="ph-fill ph-warning-octagon text-lg mt-0.5"></i>
                                    <div>
                                        <p class="font-semibold">License Expired</p>
                                        <p class="text-xs text-red-200 mt-0.5">Cannot assign driver. Compliance violation detected.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 6: Fuel Estimation -->
                        <div class="glass-panel rounded-3xl p-6 border-white/5 relative overflow-hidden bg-gradient-to-br from-white/5 to-transparent">
                            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-white/5">
                                <div class="w-8 h-8 rounded-lg bg-status-success/10 flex items-center justify-center border border-status-success/20 text-status-success">
                                    <i class="ph-fill ph-gas-pump text-lg"></i>
                                </div>
                                <h3 class="text-base font-medium text-white">6. Fuel Estimation</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Efficiency -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Efficiency (km/l)</label>
                                        <input type="number" id="fuelEff" placeholder="e.g. 12" class="form-input" min="1">
                                    </div>
                                    <!-- Rate -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Rate (₹/L)</label>
                                        <input type="number" id="fuelRate" placeholder="e.g. 96" class="form-input" min="1">
                                    </div>
                                </div>
                                
                                <!-- Total Est Cost Preview -->
                                <div class="bg-black/40 border border-white/10 rounded-xl p-4 flex items-center justify-between">
                                    <div>
                                        <p class="text-[10px] font-medium text-brand-gold uppercase tracking-wider">Estimated Cost Preview</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Calculated automatically</p>
                                    </div>
                                    <h4 class="text-2xl font-bold text-white font-mono" id="displayCost">₹ 0.00</h4>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Bottom Action Bar -->
                <div class="glass-panel rounded-3xl p-6 border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4 mt-4 relative overflow-hidden sticky bottom-0 z-20 shadow-[0_-10px_40px_rgba(0,0,0,0.5)]">
                    <div class="flex items-center gap-2 text-xs text-gray-400 font-medium">
                        <i class="ph-fill ph-info text-brand-gold"></i> Complete all required fields to dispatch.
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                        <button type="button" class="px-6 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-colors w-full sm:w-auto text-center">
                            Cancel
                        </button>
                        <button type="button" class="btn-outline px-6 py-3 rounded-xl text-sm font-medium w-full sm:w-auto text-center">
                            Save as Draft
                        </button>
                        <button type="button" id="dispatchTripBtn" class="btn-premium px-8 py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2 w-full sm:w-auto shadow-[0_0_20px_rgba(212,175,55,0.2)]">
                            <i class="ph-bold ph-paper-plane-right text-lg"></i> Dispatch Trip
                        </button>
                    </div>
                </div>
            </form>

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

    <!-- UI Interaction Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // Elements
            const cargoWeight = document.getElementById('cargoWeight');
            const weightWarningBox = document.getElementById('weightWarningBox');
            
            const vehicleAssign = document.getElementById('vehicleAssign');
            const vehicleMatchBox = document.getElementById('vehicleMatchBox');
            
            const driverAssign = document.getElementById('driverAssign');
            const driverMatchBox = document.getElementById('driverMatchBox');
            
            const routeDistance = document.getElementById('routeDistance');
            const fuelEff = document.getElementById('fuelEff');
            const fuelRate = document.getElementById('fuelRate');
            const displayCost = document.getElementById('displayCost');

            const dispatchBtn = document.getElementById('dispatchTripBtn');

            // Hardcoded max capacity limit for the dummy data scenario
            const SYSTEM_MAX_CAPACITY = 2500;

            // 1. Cargo Weight Logic
            cargoWeight.addEventListener('input', (e) => {
                const weight = parseFloat(e.target.value) || 0;
                if (weight > SYSTEM_MAX_CAPACITY) {
                    weightWarningBox.classList.remove('hidden');
                    cargoWeight.classList.add('input-error');
                } else {
                    weightWarningBox.classList.add('hidden');
                    cargoWeight.classList.remove('input-error');
                }
                validateVehicleMatch(); // Re-validate vehicle if weight changes
            });

            // 2. Vehicle Selection Logic
            function validateVehicleMatch() {
                const weight = parseFloat(cargoWeight.value) || 0;
                const selectedOpt = vehicleAssign.options[vehicleAssign.selectedIndex];
                
                if (vehicleAssign.value === "") {
                    vehicleMatchBox.classList.add('hidden');
                    return;
                }

                const cap = parseFloat(selectedOpt.getAttribute('data-capacity'));
                
                if (weight > cap) {
                    // Show warning
                    vehicleMatchBox.classList.remove('hidden');
                    vehicleMatchBox.className = 'rounded-xl p-3 flex items-start gap-2 text-sm border border-status-danger/30 bg-status-danger/10 text-status-danger mt-3';
                    vehicleMatchBox.innerHTML = `
                        <i class="ph-fill ph-warning-octagon text-lg mt-0.5"></i>
                        <div>
                            <p class="font-semibold">Vehicle Capacity Exceeded</p>
                            <p class="text-xs text-red-200 mt-0.5">Selected vehicle (Max ${cap}kg) cannot handle the current cargo weight (${weight}kg).</p>
                        </div>
                    `;
                    vehicleAssign.classList.add('input-error');
                } else {
                    // Show Success
                    vehicleMatchBox.classList.remove('hidden');
                    vehicleMatchBox.className = 'rounded-xl p-3 flex items-center gap-2 text-sm border border-status-success/30 bg-status-success/10 text-status-success mt-3';
                    vehicleMatchBox.innerHTML = `
                        <i class="ph-fill ph-check-circle text-lg"></i>
                        <div>
                            <p class="font-semibold text-xs">Vehicle assignment valid. Capacity available.</p>
                        </div>
                    `;
                    vehicleAssign.classList.remove('input-error');
                }
            }
            vehicleAssign.addEventListener('change', validateVehicleMatch);

            // 3. Driver Selection Logic
            driverAssign.addEventListener('change', (e) => {
                const selectedOpt = e.target.options[e.target.selectedIndex];
                const status = selectedOpt.getAttribute('data-status');
                
                if (status === 'Expired') {
                    driverMatchBox.classList.remove('hidden');
                    driverAssign.classList.add('input-error');
                } else {
                    driverMatchBox.classList.add('hidden');
                    driverAssign.classList.remove('input-error');
                }
            });

            // 4. Fuel Estimation Calculation
            function calculateFuelCost() {
                const dist = parseFloat(routeDistance.value) || 0;
                const eff = parseFloat(fuelEff.value) || 0;
                const rate = parseFloat(fuelRate.value) || 0;
                
                if (dist > 0 && eff > 0 && rate > 0) {
                    const cost = (dist / eff) * rate;
                    displayCost.textContent = `₹ ${cost.toFixed(2)}`;
                } else {
                    displayCost.textContent = `₹ 0.00`;
                }
            }
            routeDistance.addEventListener('input', calculateFuelCost);
            fuelEff.addEventListener('input', calculateFuelCost);
            fuelRate.addEventListener('input', calculateFuelCost);

            // 5. Button Submit Animation
            dispatchBtn.addEventListener('click', (e) => {
                e.preventDefault(); // Stop actual submit for demo
                
                // Add loading state
                const originalHtml = dispatchBtn.innerHTML;
                dispatchBtn.disabled = true;
                dispatchBtn.innerHTML = `<i class="ph ph-spinner animate-spin text-lg"></i> Processing Dispatch...`;
                
                // Simulate network request
                setTimeout(() => {
                    alert("Trip created and dispatched successfully!");
                    dispatchBtn.innerHTML = originalHtml;
                    dispatchBtn.disabled = false;
                }, 1500);
            });
        });
    </script>
</body>
</html>