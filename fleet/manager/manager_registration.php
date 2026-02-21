<?php
session_start();
require "../dbconnect.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../login.php");
    exit();
}
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
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            gold: '#D4AF37',
                            dark: '#0A0A0B',
                            light: '#F8F9FA',
                            accent: '#1E1E24'
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #050505;
            color: #ffffff;
            overflow-x: hidden;
        }
        
        /* Dynamic Background Canvas */
        #bg-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            background: radial-gradient(circle at center, #11111a 0%, #050505 100%);
        }

        /* Glassmorphism Panel */
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        /* Premium Input Styles */
        .input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .premium-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            padding-left: 3rem;
            color: white;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .premium-input:focus {
            border-color: #D4AF37;
            background: rgba(255, 255, 255, 0.05);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.15);
        }

        .premium-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        select.premium-input {
            appearance: none;
        }

        select.premium-input option {
            background-color: #0A0A0B;
            color: white;
            padding: 10px;
        }

        /* Submit Button */
        .btn-premium {
            background: linear-gradient(135deg, #D4AF37 0%, #AA8C2C 100%);
            color: #050505;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            border: none;
            z-index: 1;
        }

        .btn-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: all 0.6s ease;
            z-index: -1;
        }

        .btn-premium:hover::before {
            left: 100%;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #050505;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(212, 175, 55, 0.3);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(212, 175, 55, 0.6);
        }

        /* Ambient Glow */
        .ambient-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
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
                    <a href="../logout.php" class="text-gray-500 hover:text-status-danger transition-colors ml-2" title="Logout">
                        <i class="ph ph-sign-out text-2xl"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Scrollable Area -->
        <main class="flex-grow w-full flex flex-col lg:flex-row items-center justify-center px-6 lg:px-20 py-8 z-10 relative gap-12 lg:gap-24">
        
        <!-- Left Side: Branding & Description Panel -->
        <div class="w-full lg:w-5/12 flex flex-col items-center lg:items-start text-center lg:text-left animate-float" style="animation-duration: 7s;">
            <div class="inline-block px-4 py-1.5 rounded-full border border-brand-gold/30 bg-brand-gold/10 text-brand-gold text-xs font-medium tracking-widest uppercase mb-6 backdrop-blur-sm">
                Smart Fleet Management & Logistics Optimization
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                Accelerate Your <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-gold via-yellow-200 to-white">Operations.</span>
            </h1>
            <p class="text-base md:text-lg text-gray-400 max-w-md font-light leading-relaxed">
                Manage vehicles, drivers, trips, and operations efficiently with intelligent insights and real-time fleet control.
            </p>
            
            <div class="mt-10 grid grid-cols-2 gap-6 opacity-80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-brand-gold">
                        <i class="ph ph-shield-check text-xl"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-white font-medium text-sm">Secure</p>
                        <p class="text-xs text-gray-500">End-to-end encrypted</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-brand-gold">
                        <i class="ph ph-lightning text-xl"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-white font-medium text-sm">Real-Time</p>
                        <p class="text-xs text-gray-500">Live data sync</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div class="w-full lg:w-6/12 max-w-lg">
            <div class="glass-panel rounded-3xl p-8 md:p-10 relative overflow-hidden">
                <!-- Decorative top glow -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-brand-gold to-transparent opacity-50"></div>
                
                <div class="mb-8">
                    <h2 class="text-3xl font-semibold text-white mb-2">Registration</h2>
                    <p class="text-gray-400 text-sm">Create your command center account.</p>
                </div>

                <form method="POST" action="reg_logic.php">
                    
                    <div class="input-group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph ph-user text-gray-400 text-lg"></i>
                        </div>
                        <input type="text" id="fullName" name="fullName" class="premium-input" placeholder="Full Name">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                        <div class="input-group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ph ph-envelope-simple text-gray-400 text-lg"></i>
                            </div>
                            <input type="email" id="email" name="email" class="premium-input" placeholder="Email Address">
                        </div>
                        
                        <div class="input-group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ph ph-phone text-gray-400 text-lg"></i>
                            </div>
                            <input type="tel" id="phone" name="phone" class="premium-input" placeholder="Phone Number">
                        </div>
                    </div>

                    <div class="input-group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph ph-identification-badge text-gray-400 text-lg"></i>
                        </div>
                        <select id="role" name="role" class="premium-input text-gray-300">
                            <option value="" disabled selected>Select Your Role</option>
                            <option value="dispatcher">Dispatcher</option>
                            <option value="safety_officer">Safety Officer</option>
                            <option value="finance_officer">Finance Officer</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class="ph ph-caret-down text-gray-400"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                        <div class="input-group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ph ph-lock-key text-gray-400 text-lg"></i>
                            </div>
                            <input type="password" id="password" name="password" class="premium-input pr-10" placeholder="Password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-brand-gold transition-colors" onclick="togglePasswordVisibility('password', 'eyeIcon1')">
                                <i class="ph ph-eye text-lg" id="eyeIcon1"></i>
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ph ph-shield-check text-gray-400 text-lg"></i>
                            </div>
                            <input type="password" id="confirmPassword" name="confirmPassword" class="premium-input pr-10" placeholder="Confirm Password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-brand-gold transition-colors" onclick="togglePasswordVisibility('confirmPassword', 'eyeIcon2')">
                                <i class="ph ph-eye text-lg" id="eyeIcon2"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Error/Success Message Container -->
                    <div id="messageBox" class="hidden mb-4 p-3 rounded-lg text-sm text-center font-medium transition-all duration-300"></div>

                    <button type="submit" id="submitBtn" class="btn-premium w-full py-3.5 rounded-xl mt-2 flex justify-center items-center gap-2 text-lg">
                        <span>Register Account</span>
                        <i class="ph ph-arrow-right-bold"></i>
                    </button>
                </form>
            </div>
        </div>
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
        
    </script>
</body>
</html>
