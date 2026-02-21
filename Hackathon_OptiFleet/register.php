<?php
// ================= BACKEND PLACEHOLDER =================
// 1. Check if form is submitted ($_POST)
// 2. Validate inputs (email, password match, phone)
// 3. Connect to database
// 4. Insert user into users table
// 5. Redirect to login page after success
// =======================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Premium Registration</title>
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
<body class="min-h-screen flex flex-col font-sans relative selection:bg-brand-gold selection:text-black">

    <!-- Dynamic Background -->
    <canvas id="bg-canvas"></canvas>
    
    <!-- Ambient Lighting Effects -->
    <div class="ambient-glow top-[-200px] left-[-200px]"></div>
    <div class="ambient-glow bottom-[-200px] right-[-200px]" style="background: radial-gradient(circle, rgba(10, 150, 255, 0.05) 0%, transparent 70%);"></div>

    <!-- Header (shrink-0 prevents squishing) -->
    <header class="w-full py-6 px-8 md:px-16 flex justify-between items-center z-20 relative shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-gold to-yellow-600 flex items-center justify-center shadow-lg shadow-brand-gold/20">
                <i class="ph ph-steering-wheel text-black text-2xl font-bold"></i>
            </div>
            <span class="text-2xl font-semibold tracking-wide text-white">
                Opti<span class="text-brand-gold">Fleet</span>
            </span>
        </div>
        <div class="hidden md:flex items-center gap-6 text-sm text-gray-400">
            <a href="#" class="hover:text-white transition-colors">Documentation</a>
        </div>
    </header>

    <!-- Main Content (flex-grow takes available space, pushing footer down) -->
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

                <form id="registrationForm" method="POST" action="" onsubmit="handleRegistration(event)">
                    
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
                            <option value="finance_manager">Finance Manager</option>
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

    <!-- Footer (mt-auto ensures bottom positioning) -->
    <footer class="w-full py-8 z-20 relative border-t border-white/5 bg-[#050505]/60 backdrop-blur-xl shrink-0 mt-auto">
        <div class="max-w-7xl mx-auto px-6 flex flex-col items-center justify-center gap-6">
            <div class="flex flex-wrap justify-center items-center gap-3 text-xs sm:text-sm font-medium">
                <div class="flex items-center gap-2 text-gray-300 bg-white/5 hover:bg-white/10 transition-colors px-4 py-2 rounded-full border border-white/10">
                    <i class="ph-fill ph-code text-brand-gold text-lg"></i> 
                    Divy Choksi <span class="text-gray-500 font-normal">| Frontend</span>
                </div>
                <div class="flex items-center gap-2 text-gray-300 bg-white/5 hover:bg-white/10 transition-colors px-4 py-2 rounded-full border border-white/10">
                    <i class="ph-fill ph-database text-blue-400 text-lg"></i> 
                    Dhruv Pandya <span class="text-gray-500 font-normal">| Backend</span>
                </div>
                <div class="flex items-center gap-2 text-gray-300 bg-white/5 hover:bg-white/10 transition-colors px-4 py-2 rounded-full border border-white/10">
                    <i class="ph-fill ph-file-text text-emerald-400 text-lg"></i> 
                    Jay Gajjar <span class="text-gray-500 font-normal">| Documentation</span>
                </div>
                <div class="flex items-center gap-2 text-gray-300 bg-white/5 hover:bg-white/10 transition-colors px-4 py-2 rounded-full border border-white/10">
                    <i class="ph-fill ph-check-circle text-purple-400 text-lg"></i> 
                    Naivedi Binjwa <span class="text-gray-500 font-normal">| Validation</span>
                </div>
            </div>
            <p class="text-xs text-gray-600 tracking-wider uppercase">&copy; 2026 OptiFleet | All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Interactive Scripts & Validation -->
    <script>
        // --- Password Visibility Toggle ---
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('ph-eye');
                eyeIcon.classList.add('ph-eye-slash');
                eyeIcon.classList.add('text-brand-gold');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('ph-eye-slash');
                eyeIcon.classList.add('ph-eye');
                eyeIcon.classList.remove('text-brand-gold');
            }
        }

        // --- Client-Side Form Validation ---
        function handleRegistration(event) {
            // DHRUV: Remove event.preventDefault() below when connecting actual PHP backend
            event.preventDefault(); 
            
            const fullName = document.getElementById('fullName').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const role = document.getElementById('role').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const messageBox = document.getElementById('messageBox');
            const submitBtn = document.getElementById('submitBtn');

            // Reset message box
            messageBox.className = 'hidden mb-4 p-3 rounded-lg text-sm text-center font-medium transition-all duration-300';
            
            // Helper function to show errors
            const showError = (msg) => {
                messageBox.textContent = msg;
                messageBox.classList.add('bg-red-500/10', 'text-red-400', 'border', 'border-red-500/20', 'block');
                messageBox.classList.remove('hidden');
            };

            // Validations
            if (!fullName || !email || !phone || !role || !password || !confirmPassword) {
                return showError('Please fill out all required fields.');
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                return showError('Please enter a valid email address.');
            }

            if (phone.length < 10) {
                return showError('Phone number must be at least 10 digits.');
            }

            if (password.length < 8) {
                return showError('Password must be at least 8 characters long.');
            }

            if (password !== confirmPassword) {
                return showError('Passwords do not match. Please try again.');
            }

            // Simulate UI Success State before form submission
            submitBtn.innerHTML = '<i class="ph ph-spinner animate-spin"></i> Validating...';
            submitBtn.style.opacity = '0.8';
            submitBtn.disabled = true;

            setTimeout(() => {
                messageBox.textContent = 'Validation successful! Setting up your workspace...';
                messageBox.className = 'mb-4 p-3 rounded-lg text-sm text-center font-medium bg-green-500/10 text-green-400 border border-green-500/20 block';
                
                submitBtn.innerHTML = 'Workspace Ready <i class="ph ph-check-circle"></i>';
                submitBtn.style.background = '#22c55e'; // Tailwind green
                submitBtn.style.color = '#fff';
                
                // Here is where the form would actually submit to the backend.
                // setTimeout(() => { document.getElementById('registrationForm').submit(); }, 1500);
                
                // For demo reset:
                setTimeout(() => {
                    document.getElementById('registrationForm').reset();
                    submitBtn.innerHTML = '<span>Register Account</span><i class="ph ph-arrow-right-bold"></i>';
                    submitBtn.style = '';
                    submitBtn.disabled = false;
                    messageBox.classList.add('hidden');
                }, 3000);
            }, 1500);
        }

        // --- Luxurious Dynamic Canvas Background ---
        const canvas = document.getElementById('bg-canvas');
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }

        window.addEventListener('resize', resize);
        resize();

        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * 0.5;
                this.vy = (Math.random() - 0.5) * 0.5;
                this.radius = Math.random() * 2;
                const colors = ['rgba(212, 175, 55, 0.4)', 'rgba(255, 255, 255, 0.1)'];
                this.color = colors[Math.floor(Math.random() * colors.length)];
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.color;
                ctx.fill();
            }
        }

        const particleCount = Math.min(window.innerWidth / 15, 100);
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }

        let mouse = { x: null, y: null };
        window.addEventListener('mousemove', (e) => {
            mouse.x = e.x;
            mouse.y = e.y;
        });
        window.addEventListener('mouseout', () => {
            mouse.x = null;
            mouse.y = null;
        });

        function animate() {
            ctx.clearRect(0, 0, width, height);

            for (let i = 0; i < particles.length; i++) {
                particles[i].update();
                particles[i].draw();

                for (let j = i; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < 120) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(255, 255, 255, ${0.05 - distance/2400})`;
                        ctx.lineWidth = 0.5;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }

                if (mouse.x != null && mouse.y != null) {
                    const dx = mouse.x - particles[i].x;
                    const dy = mouse.y - particles[i].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    if (distance < 150) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(212, 175, 55, ${0.15 - distance/1000})`;
                        ctx.lineWidth = 1;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(mouse.x, mouse.y);
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }

        animate();
    </script>
</body>
</html>