
<?php
session_start();

$message = $_SESSION['message'] ?? "";
$messageType = $_SESSION['messageType'] ?? "";

unset($_SESSION['message']);
unset($_SESSION['messageType']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Premium Login</title>
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
            font-size: 1rem;
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

        /* Custom Checkbox */
        .custom-checkbox {
            appearance: none;
            width: 1.15rem;
            height: 1.15rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.25rem;
            background: rgba(255, 255, 255, 0.05);
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }
        
        .custom-checkbox:checked {
            background-color: #D4AF37;
            border-color: #D4AF37;
        }

        .custom-checkbox:checked::after {
            content: '✓';
            position: absolute;
            color: black;
            font-size: 0.8rem;
            font-weight: bold;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
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

    <!-- Header -->
    <header class="w-full py-6 px-8 md:px-16 flex justify-between items-center z-20 relative">
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

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center px-6 py-10 z-10 relative">
        
        <div class="w-full max-w-md animate-float" style="animation-duration: 8s;">
            <div class="glass-panel rounded-3xl p-8 md:p-10 relative overflow-hidden">
                <!-- Decorative top glow -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-brand-gold to-transparent opacity-50"></div>
                
                <div class="mb-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center">
                        <i class="ph ph-user-circle text-4xl text-brand-gold"></i>
                    </div>
                    <h2 class="text-3xl font-semibold text-white mb-2">Welcome Back</h2>
                    <p class="text-gray-400 text-sm">Sign in to the Command Center</p>
                </div>
<?php if(!empty($message)): ?>
    <div class="mb-6 p-3 rounded-lg text-sm text-center font-medium 
    <?php echo $messageType === 'success' 
        ? 'bg-green-500/10 text-green-400 border border-green-500/20' 
        : 'bg-red-500/10 text-red-400 border border-red-500/20'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

                <!-- Form structure modified slightly to work with pure JS for the smooth UI demo, 
                     but retains form action and method for real backend use -->
                <form id="loginForm" method="POST" action="login_logic.php">
                    
                    <!-- Username Input -->
                    <div class="input-group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph ph-user text-gray-400 text-lg"></i>
                        </div>
                        <input type="text" id="username" name="username" required class="premium-input" placeholder="Username or Email">
                    </div>

                    <!-- Password Input with Eye Toggle -->
                    <div class="input-group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph ph-lock-key text-gray-400 text-lg"></i>
                        </div>
                        <input type="password" id="password" name="password" required class="premium-input pr-12" placeholder="Password">
                        <!-- Eye Icon -->
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-brand-gold transition-colors" onclick="togglePasswordVisibility()">
                            <i class="ph ph-eye text-xl" id="eyeIcon"></i>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mt-5 mb-8">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="custom-checkbox">
                            <span class="text-sm text-gray-400 group-hover:text-white transition-colors">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-brand-gold hover:text-white transition-colors">Forgot password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" class="btn-premium w-full py-3.5 rounded-xl flex justify-center items-center gap-2 text-lg">
                        <span>Login</span>
                        <i class="ph ph-sign-in"></i>
                    </button>

                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-6 z-20 relative border-t border-white/5 bg-black/20 backdrop-blur-md mt-auto">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-gray-500">&copy; 2026 OptiFleet Management System. All rights reserved.</p>
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-xs font-medium">
                <span class="flex items-center gap-1.5 text-gray-400">
                    <i class="ph-fill ph-code text-brand-gold"></i> Divy Choksi <span class="opacity-50 font-normal">Frontend</span>
                </span>
                <span class="flex items-center gap-1.5 text-gray-400">
                    <i class="ph-fill ph-database text-blue-400"></i> Dhruv Pandya <span class="opacity-50 font-normal">Backend</span>
                </span>
                <span class="flex items-center gap-1.5 text-gray-400">
                    <i class="ph-fill ph-file-text text-emerald-400"></i> Jay Gajjar <span class="opacity-50 font-normal">Documentation</span>
                </span>
                <span class="flex items-center gap-1.5 text-gray-400">
                    <i class="ph-fill ph-check-circle text-purple-400"></i> Naivedi Binjwa <span class="opacity-50 font-normal">Validation</span>
                </span>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts -->
    <script>
        // --- Password Visibility Toggle ---
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('ph-eye');
                eyeIcon.classList.add('ph-eye-slash');
                eyeIcon.classList.add('text-brand-gold'); // Highlight when visible
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('ph-eye-slash');
                eyeIcon.classList.add('ph-eye');
                eyeIcon.classList.remove('text-brand-gold');
            }
        }

        // --- Simulated Login Animation (Remove if relying purely on PHP submit) ---
        function handleLogin(event) {
            // Uncomment the line below if you want the form to submit to the PHP block directly
            // return true; 

            event.preventDefault(); // Prevent standard submission for UI demo
            
            const submitBtn = document.getElementById('submitBtn');
            const originalBtnContent = submitBtn.innerHTML;

            // Loading state
            submitBtn.innerHTML = '<i class="ph ph-spinner animate-spin"></i> Authenticating...';
            submitBtn.style.opacity = '0.8';
            submitBtn.disabled = true;

            // Simulate network request
            setTimeout(() => {
                submitBtn.innerHTML = 'Success <i class="ph ph-check-circle"></i>';
                submitBtn.style.background = '#22c55e'; // Tailwind green
                submitBtn.style.color = '#fff';
                
                // Reset button or redirect after delay
                setTimeout(() => {
                    // window.location.href = "dashboard.php"; // Example redirect
                    submitBtn.innerHTML = originalBtnContent;
                    submitBtn.style = '';
                    submitBtn.disabled = false;
                }, 2000);
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
