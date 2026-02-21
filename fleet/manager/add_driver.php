<?php
session_start();
require "../dbconnect.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "manager") {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['add_driver'])) {

    $name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $license = strtoupper(trim($_POST['license_number']));
    $expiry = $_POST['license_expiry'];
    $category = $_POST['license_category'];
    $status = $_POST['status'];

    // Check duplicate license
    $check = $conn->prepare("SELECT driver_id FROM drivers WHERE license_number = ?");
    $check->execute([$license]);

    if ($check->rowCount() > 0) {
        $error = "License number already exists!";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO drivers 
            (full_name, phone, license_number, license_category, license_expiry, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([$name, $phone, $license, $category, $expiry, $status]);

        header("Location: manager_driver.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Add Driver</title>
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
        body { background-color: #050505; color: #ffffff; overflow-x: hidden; }
        
        /* Dynamic Background Canvas */
        #bg-canvas { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; background: radial-gradient(circle at center, #11111a 0%, #050505 100%); }

        /* Glassmorphism Styles */
        .glass-panel {
            background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.06); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        /* Premium Input Styles */
        .input-group { position: relative; }
        
        .premium-input {
            width: 100%; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem; padding: 0.875rem 1rem 0.875rem 3rem; color: white; font-size: 0.95rem;
            transition: all 0.3s ease; outline: none;
        }
        .premium-input:focus { border-color: #D4AF37; background: rgba(255, 255, 255, 0.05); box-shadow: 0 0 15px rgba(212, 175, 55, 0.15); }
        .premium-input::placeholder { color: rgba(255, 255, 255, 0.3); }
        select.premium-input { appearance: none; }
        select.premium-input option { background-color: #0A0A0B; color: white; }

        /* Ambient Glows */
        .ambient-glow { position: fixed; width: 600px; height: 600px; background: radial-gradient(circle, rgba(212, 175, 55, 0.06) 0%, transparent 70%); border-radius: 50%; pointer-events: none; z-index: 0; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(212, 175, 55, 0.4); }

        /* Premium Buttons */
        .btn-premium { background: linear-gradient(135deg, #D4AF37 0%, #AA8C2C 100%); color: #050505; font-weight: 600; transition: all 0.3s ease; border: none; }
        .btn-premium:hover { box-shadow: 0 0 15px rgba(212, 175, 55, 0.4); transform: translateY(-1px); }
        
        .btn-outline { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; transition: all 0.3s ease; }
        .btn-outline:hover { background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="h-screen flex selection:bg-brand-gold selection:text-black relative">

    <!-- Dynamic Background -->
    <canvas id="bg-canvas"></canvas>

    <!-- Ambient Lighting -->
    <div class="ambient-glow top-[-200px] right-[-100px]"></div>
    <div class="ambient-glow bottom-[-300px] left-[-200px]" style="background: radial-gradient(circle, rgba(10, 150, 255, 0.03) 0%, transparent 70%);"></div>

    <!-- Sidebar Navigation -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full relative z-10 overflow-y-auto">
        
        <!-- Top Header Bar -->
        <header class="h-20 glass-panel flex items-center justify-between px-8 border-b border-white/5 shrink-0">
            <div class="flex items-center gap-4">
                <a href="manager_driver.php" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                    <i class="ph ph-arrow-left text-lg"></i>
                </a>
                <h1 class="text-2xl font-semibold">Driver Management</h1>
            </div>
            
            <div class="flex items-center gap-4 pl-6 border-l border-white/10 hidden sm:flex">
                <div class="text-right">
                    <p class="text-sm font-medium text-white">Manager Portal</p>
                    <p class="text-xs text-brand-gold">Authorized Access</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-brand-gold/30">
                    <i class="ph-fill ph-shield-check text-xl text-brand-gold"></i>
                </div>
            </div>
        </header>

        <!-- Centered Form Container -->
        <main class="flex-1 flex items-center justify-center p-6 md:p-10">
            
            <div class="w-full max-w-2xl">
                
                <!-- Display Error if PHP validation fails -->
                <?php if(isset($error) && $error): ?>
                    <div class="bg-status-danger/10 border border-status-danger/30 text-status-danger px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                        <p class="text-sm font-medium"><?= htmlspecialchars($error) ?></p>
                    </div>
                <?php endif; ?>

                <div class="glass-panel rounded-3xl p-8 md:p-10 relative overflow-hidden">
                    <!-- Decorative top glow -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-brand-gold to-transparent opacity-50"></div>
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-brand-gold/10 flex items-center justify-center border border-brand-gold/20">
                            <i class="ph-fill ph-identification-card text-2xl text-brand-gold"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold text-white">Register New Driver</h2>
                            <p class="text-sm text-gray-400">Add a new driver to the OptiFleet system.</p>
                        </div>
                    </div>

                    <form method="POST" action="" class="space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Full Name -->
                            <div>
                                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-2 ml-1">Full Name <span class="text-status-danger">*</span></label>
                                <div class="input-group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="ph ph-user text-lg"></i>
                                    </div>
                                    <input type="text" name="full_name" class="premium-input" placeholder="e.g. Robert Jensen" required>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-2 ml-1">Phone Number <span class="text-status-danger">*</span></label>
                                <div class="input-group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="ph ph-phone text-lg"></i>
                                    </div>
                                    <input type="text" name="phone" class="premium-input" placeholder="Enter 10-digit number" required>
                                </div>
                            </div>

                            <!-- License Number -->
                            <div>
                                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-2 ml-1">License Number <span class="text-status-danger">*</span></label>
                                <div class="input-group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="ph ph-address-book text-lg"></i>
                                    </div>
                                    <input type="text" name="license_number" class="premium-input uppercase" placeholder="DL-123456789" required>
                                </div>
                            </div>

                            <!-- License Expiry -->
                            <div>
                                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-2 ml-1">License Expiry <span class="text-status-danger">*</span></label>
                                <div class="input-group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="ph ph-calendar-blank text-lg"></i>
                                    </div>
                                    <!-- In webkit browsers, date inputs have default internal padding, keeping custom styling minimal but effective -->
                                    <input type="date" name="license_expiry" class="premium-input" required>
                                </div>
                            </div>

                            <!-- License Category -->
                            <div>
                                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-2 ml-1">Vehicle Clearance <span class="text-status-danger">*</span></label>
                                <div class="input-group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="ph ph-steering-wheel text-lg"></i>
                                    </div>
                                    <select name="license_category" class="premium-input text-gray-300" required>
                                        <option value="" disabled selected>Select clearance</option>
                                        <option value="Truck">Heavy Truck</option>
                                        <option value="Van">Cargo Van</option>
                                        <option value="Bike">Delivery Bike</option>
                                        <option value="All">All Vehicles</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="ph ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-2 ml-1">Initial Status <span class="text-status-danger">*</span></label>
                                <div class="input-group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="ph ph-toggle-left text-lg"></i>
                                    </div>
                                    <select name="status" class="premium-input text-gray-300" required>
                                        <option value="" disabled selected>Select status</option>
                                        <option value="on_duty">On Duty</option>
                                        <option value="off_duty">Off Duty</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="ph ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-white/10 mt-6">
                            <a href="manager_driver.php" class="text-sm text-gray-400 hover:text-white transition-colors flex items-center gap-2">
                                <i class="ph ph-arrow-left"></i> Back to Drivers
                            </a>
                            <button type="submit" name="add_driver" class="btn-premium px-8 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
                                <i class="ph-fill ph-user-plus"></i> Add Driver
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Interactive Canvas Background Script -->
    <script>
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
                const colors = ['rgba(212, 175, 55, 0.3)', 'rgba(255, 255, 255, 0.05)'];
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
        for (let i = 0; i < particleCount; i++) { particles.push(new Particle()); }

        let mouse = { x: null, y: null };
        window.addEventListener('mousemove', (e) => { mouse.x = e.x; mouse.y = e.y; });
        window.addEventListener('mouseout', () => { mouse.x = null; mouse.y = null; });

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
                        ctx.strokeStyle = `rgba(255, 255, 255, ${0.03 - distance/4000})`;
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
                        ctx.strokeStyle = `rgba(212, 175, 55, ${0.1 - distance/1500})`;
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