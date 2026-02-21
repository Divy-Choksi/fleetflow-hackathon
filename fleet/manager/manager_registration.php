<?php
// ================= BACKEND PLACEHOLDER =================
// 1. Verify manager session and role
// 2. Check form submission using POST
// 3. Validate input data
// 4. Hash password
// 5. Insert new user into users table
// 6. Redirect to user list or show success message
// =======================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | User Registration</title>
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
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-truck text-xl"></i>
                <span class="font-medium">Vehicle Registry</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-users text-xl"></i>
                <span class="font-medium">Drivers</span>
            </a>

            <!-- ACTIVE STATE: Registration -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand-gold/10 text-brand-gold border border-brand-gold/20 transition-all">
                <i class="ph-fill ph-user-plus text-xl"></i>
                <span class="font-medium">Registration</span>
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
                <h2 class="text-2xl font-semibold text-white">User Registration</h2>
                <p class="text-sm text-gray-400 mt-1">Create new system users for operational roles.</p>
            </div>

            <!-- Registration Form Area -->
            <div class="glass-panel rounded-3xl p-8 max-w-4xl mx-auto w-full mb-8">
                <div class="flex items-center gap-3 mb-8 pb-4 border-b border-white/5">
                    <div class="w-10 h-10 rounded-xl bg-brand-gold/10 flex items-center justify-center border border-brand-gold/20">
                        <i class="ph-fill ph-identification-badge text-xl text-brand-gold"></i>
                    </div>
                    <h3 class="text-lg font-medium text-white">Account Information</h3>
                </div>

                <form id="registrationForm" class="space-y-6" novalidate>
                    <!-- Row 1: Name & Role -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Full Name</label>
                            <div class="relative">
                                <i class="ph ph-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                <input type="text" id="fullName" placeholder="Enter full name" class="form-input pl-10" required>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Full name is required (min 3 chars).</p>
                        </div>

                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Assign Role</label>
                            <div class="relative">
                                <i class="ph ph-briefcase absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 z-10"></i>
                                <select id="userRole" class="form-input pl-10 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.7rem] bg-no-repeat bg-[position:right_1rem_center]" required>
                                    <option value="" disabled selected>Select an operational role</option>
                                    <option value="dispatcher">Dispatcher</option>
                                    <option value="safety_officer">Safety Officer</option>
                                    <option value="finance_manager">Finance Manager</option>
                                </select>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Please select a user role.</p>
                        </div>
                    </div>

                    <!-- Row 2: Email & Phone -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Email Address</label>
                            <div class="relative">
                                <i class="ph ph-envelope-simple absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                <input type="email" id="email" placeholder="example@optifleet.com" class="form-input pl-10" required>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Please enter a valid email address.</p>
                        </div>

                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Phone Number</label>
                            <div class="relative">
                                <i class="ph ph-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                <input type="tel" id="phone" placeholder="Enter 10-digit phone number" class="form-input pl-10" required>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Phone number must be exactly 10 digits.</p>
                        </div>
                    </div>

                    <!-- Row 3: Passwords -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Password</label>
                            <div class="relative">
                                <i class="ph ph-lock-key absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                <input type="password" id="password" placeholder="Create a strong password" class="form-input pl-10 pr-10" required>
                                <button type="button" class="toggle-password absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors" data-target="password">
                                    <i class="ph ph-eye"></i>
                                </button>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Password must be at least 8 characters.</p>
                        </div>

                        <div class="space-y-2 relative">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Confirm Password</label>
                            <div class="relative">
                                <i class="ph ph-check-circle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                <input type="password" id="confirmPassword" placeholder="Confirm your password" class="form-input pl-10 pr-10" required>
                                <button type="button" class="toggle-password absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors" data-target="confirmPassword">
                                    <i class="ph ph-eye"></i>
                                </button>
                            </div>
                            <p class="text-status-danger text-[11px] hidden error-msg">Passwords do not match.</p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-6 mt-4 border-t border-white/5 flex items-center justify-between">
                        <p class="text-xs text-gray-500"><i class="ph-fill ph-info text-brand-gold mr-1"></i> Ensure all fields are valid before submitting.</p>
                        <button type="submit" id="submitBtn" class="btn-premium px-8 py-3 rounded-xl text-sm font-bold flex items-center gap-2" disabled>
                            <i class="ph-fill ph-user-circle-plus text-lg"></i> Create User Account
                        </button>
                    </div>
                </form>
            </div>

            <!-- Custom Footer -->
            <footer class="mt-auto pt-6 border-t border-white/5 pb-4">
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
            const form = document.getElementById('registrationForm');
            const btnSubmit = document.getElementById('submitBtn');
            
            // Inputs
            const inputs = {
                fullName: document.getElementById('fullName'),
                role: document.getElementById('userRole'),
                email: document.getElementById('email'),
                phone: document.getElementById('phone'),
                password: document.getElementById('password'),
                confirmPassword: document.getElementById('confirmPassword')
            };

            // Validation Rules
            const validators = {
                fullName: (val) => val.trim().length >= 3,
                role: (val) => val !== "",
                email: (val) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val),
                phone: (val) => /^\d{10}$/.test(val.trim()),
                password: (val) => val.length >= 8,
                confirmPassword: (val) => val === inputs.password.value && val.length >= 8
            };

            // Validate Individual Field
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

            // Check entire form to enable/disable button
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

            // Attach Event Listeners
            for (const key in inputs) {
                inputs[key].addEventListener('input', (e) => {
                    // Specific fix for confirm password mapping
                    if (key === 'password' && inputs.confirmPassword.value !== "") {
                        validateField(inputs.confirmPassword, 'confirmPassword');
                    }
                    
                    validateField(e.target, key);
                    checkFormValidity();
                });
                
                inputs[key].addEventListener('blur', (e) => {
                    validateField(e.target, key);
                });
            }

            // Password Toggle Logic
            const toggleButtons = document.querySelectorAll('.toggle-password');
            toggleButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('ph-eye', 'ph-eye-slash');
                        icon.classList.add('text-brand-gold');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('ph-eye-slash', 'ph-eye');
                        icon.classList.remove('text-brand-gold');
                    }
                });
            });

            // Prevent default form submission (For Demo/Frontend Validation)
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if(!btnSubmit.disabled) {
                    // Logic to process via backend/AJAX goes here
                    btnSubmit.innerHTML = `<i class="ph ph-spinner animate-spin text-lg"></i> Processing...`;
                    
                    // Mock Success Simulation
                    setTimeout(() => {
                        alert("Success: User profile created and dispatched to database.");
                        form.reset();
                        checkFormValidity();
                        btnSubmit.innerHTML = `<i class="ph-fill ph-user-circle-plus text-lg"></i> Create User Account`;
                    }, 1500);
                }
            });
        });
    </script>
</body>
</html>