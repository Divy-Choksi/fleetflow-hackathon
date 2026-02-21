<?php
// ================= BACKEND PLACEHOLDER =================

// 1. Verify safety officer session
// 2. Fetch drivers data
// 3. Check license expiry dates
// 4. Update driver status if required
// 5. Prevent assignment if license expired

// =======================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Safety Officer Dashboard</title>
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
                        status: { success: '#22c55e', warning: '#f97316', danger: '#ef4444', info: '#3b82f6', muted: '#64748b' }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #050505; color: #ffffff; overflow: hidden; }
        
        /* Dynamic Background Canvas */
        #bg-canvas { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; background: radial-gradient(circle at center, #11111a 0%, #050505 100%); }

        /* Glassmorphism Styles */
        .glass-panel {
            background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.06); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        /* Premium Input Styles */
        .premium-input {
            width: 100%; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem; padding: 0.6rem 1rem; color: white; font-size: 0.9rem;
            transition: all 0.3s ease; outline: none;
        }
        .premium-input:focus { border-color: #D4AF37; background: rgba(255, 255, 255, 0.05); box-shadow: 0 0 15px rgba(212, 175, 55, 0.15); }

        /* Ambient Glows */
        .ambient-glow { position: fixed; width: 600px; height: 600px; background: radial-gradient(circle, rgba(212, 175, 55, 0.06) 0%, transparent 70%); border-radius: 50%; pointer-events: none; z-index: 0; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(212, 175, 55, 0.4); }

        /* Premium Buttons */
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
    <aside class="w-64 glass-panel flex flex-col z-20 shrink-0 border-r border-white/5 h-full hidden md:flex">
        <div class="h-20 flex items-center px-6 border-b border-white/5 shrink-0">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-gold to-yellow-600 flex items-center justify-center mr-3 shadow-lg shadow-brand-gold/20">
                <i class="ph ph-shield-check text-black text-xl font-bold"></i>
            </div>
            <span class="text-xl font-semibold tracking-wide text-white">
                Opti<span class="text-brand-gold">Fleet</span>
            </span>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand-gold/10 text-brand-gold border border-brand-gold/20 transition-all">
                <i class="ph-fill ph-squares-four text-xl"></i><span class="font-medium">Dashboard</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-users text-xl"></i><span class="font-medium">Driver Compliance</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-identification-card text-xl"></i><span class="font-medium">License Monitoring</span>
            </a>
        </nav>
        
        <div class="p-4 border-t border-white/5 shrink-0">
            <a href="login.php" class="flex items-center justify-center gap-2 w-full py-2.5 text-sm font-medium rounded-lg text-gray-400 hover:text-status-danger hover:bg-status-danger/10 transition-colors">
                <i class="ph ph-sign-out text-lg"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full relative z-10 overflow-hidden">
        
        <!-- Top Header Bar -->
        <header class="h-20 glass-panel flex items-center justify-between px-8 border-b border-white/5 shrink-0">
            <h1 class="text-2xl font-semibold">Safety Officer Dashboard</h1>
            
            <div class="flex items-center gap-6">
                <!-- Search -->
                <div class="relative hidden md:block">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Search drivers..." class="bg-black/20 border border-white/10 rounded-full py-2 pl-10 pr-4 text-sm text-white focus:outline-none focus:border-brand-gold w-64 transition-colors">
                </div>

                <!-- Profile Actions -->
                <div class="flex items-center gap-4 pl-6 border-l border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-white">Michael Vance</p>
                            <p class="text-xs text-status-success">Safety Officer</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-status-success/30">
                            <i class="ph-fill ph-shield-check text-xl text-status-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Scrollable Dashboard Body -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8 pb-24">
            
            <!-- SECTION 1: License Expiry Monitoring -->
            <section class="glass-panel rounded-3xl p-6 relative flex flex-col">
                <div class="flex justify-between items-center mb-6 border-b border-white/10 pb-4 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-status-warning/10 border border-status-warning/20 flex items-center justify-center text-status-warning">
                            <i class="ph-fill ph-identification-card text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white">License Expiry Monitoring</h3>
                    </div>
                    
                    <!-- Internal Table Search (UI Only) -->
                    <div class="relative hidden sm:block">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Filter licenses..." class="premium-input pl-10 py-1.5 text-sm w-48">
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-white/10">
                                <th class="pb-4 pl-4">Driver ID</th>
                                <th class="pb-4">Driver Name</th>
                                <th class="pb-4">License Number</th>
                                <th class="pb-4">License Type</th>
                                <th class="pb-4 text-center">Expiry Date</th>
                                <th class="pb-4 text-center">Days Remaining</th>
                                <th class="pb-4 text-center pr-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white/5">
                            <!-- Valid Row -->
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-4 pl-4 font-medium text-gray-300">#D-102</td>
                                <td class="py-4 text-white">Alex Thompson</td>
                                <td class="py-4 font-mono text-gray-400">DL-11029-NY</td>
                                <td class="py-4 text-gray-300">Commercial (Class A)</td>
                                <td class="py-4 text-center text-gray-300">12 Oct 2028</td>
                                <td class="py-4 text-center font-medium">934</td>
                                <td class="py-4 text-center pr-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-status-success/10 text-status-success border border-status-success/20">
                                        Valid
                                    </span>
                                </td>
                            </tr>
                            <!-- Warning Row (Expiring < 30 Days) -->
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-4 pl-4 font-medium text-gray-300">#D-218</td>
                                <td class="py-4 text-white">David Chen</td>
                                <td class="py-4 font-mono text-gray-400">DL-88210-CA</td>
                                <td class="py-4 text-gray-300">Heavy Duty</td>
                                <td class="py-4 text-center text-gray-300">05 Mar 2026</td>
                                <td class="py-4 text-center font-bold text-status-warning">12</td>
                                <td class="py-4 text-center pr-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-status-warning/10 text-status-warning border border-status-warning/20">
                                        Expiring Soon
                                    </span>
                                </td>
                            </tr>
                            <!-- Critical Row (Expired - Highlighted) -->
                            <tr class="bg-status-danger/5 hover:bg-status-danger/10 transition-colors border-l-2 border-l-status-danger">
                                <td class="py-4 pl-4 font-medium text-gray-300">#D-405</td>
                                <td class="py-4 text-white font-semibold">Marcus Johnson</td>
                                <td class="py-4 font-mono text-gray-400">DL-99482-TX</td>
                                <td class="py-4 text-gray-300">Standard Delivery</td>
                                <td class="py-4 text-center text-status-danger font-medium">19 Feb 2026</td>
                                <td class="py-4 text-center font-bold text-status-danger">-2</td>
                                <td class="py-4 text-center pr-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-status-danger/20 text-status-danger border border-status-danger/30">
                                        Expired
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- SECTION 2: Driver Safety Status Management -->
            <section class="glass-panel rounded-3xl p-6 relative flex flex-col">
                <div class="flex justify-between items-center mb-6 border-b border-white/10 pb-4 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-status-info/10 border border-status-info/20 flex items-center justify-center text-status-info">
                            <i class="ph-fill ph-users-three text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white">Driver Safety Status</h3>
                    </div>
                </div>
                
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse min-w-[850px]">
                        <thead>
                            <tr class="text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-white/10">
                                <th class="pb-4 pl-4">Driver ID</th>
                                <th class="pb-4">Name</th>
                                <th class="pb-4">Phone</th>
                                <th class="pb-4 text-center">Current Status</th>
                                <th class="pb-4 text-right pr-4">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white/5">
                            <!-- Driver 1: On Duty -->
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-4 font-medium text-gray-300">#D-102</td>
                                <td class="py-4 text-white">Alex Thompson</td>
                                <td class="py-4 text-gray-400">+1 (555) 019-283</td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-status-success/10 text-status-success border border-status-success/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-status-success mr-1.5 animate-pulse"></span> On Duty
                                    </span>
                                </td>
                                <td class="py-4 text-right pr-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="btn-outline px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-white/10 transition-colors">Mark Off Duty</button>
                                        <button class="bg-status-danger/10 text-status-danger border border-status-danger/20 hover:bg-status-danger hover:text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">Suspend</button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Driver 2: Suspended -->
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-4 font-medium text-gray-300">#D-405</td>
                                <td class="py-4 text-white">Marcus Johnson</td>
                                <td class="py-4 text-gray-400">+1 (555) 882-102</td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-status-danger/10 text-status-danger border border-status-danger/20">
                                        Suspended
                                    </span>
                                </td>
                                <td class="py-4 text-right pr-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="bg-status-success/10 text-status-success border border-status-success/20 hover:bg-status-success hover:text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">Mark On Duty</button>
                                        <button class="btn-outline px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-white/10 transition-colors">Mark Off Duty</button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Driver 3: Off Duty -->
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-4 font-medium text-gray-300">#D-881</td>
                                <td class="py-4 text-white">Sarah Jenkins</td>
                                <td class="py-4 text-gray-400">+1 (555) 773-900</td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-status-muted/10 text-status-muted border border-status-muted/20 text-gray-400">
                                        Off Duty
                                    </span>
                                </td>
                                <td class="py-4 text-right pr-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="bg-status-success/10 text-status-success border border-status-success/20 hover:bg-status-success hover:text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">Mark On Duty</button>
                                        <button class="bg-status-danger/10 text-status-danger border border-status-danger/20 hover:bg-status-danger hover:text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">Suspend</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Footer -->
            <footer class="w-full py-6 mt-10 border-t border-white/5 bg-black/20 rounded-2xl">
                <div class="flex flex-col items-center justify-center gap-4">
                    <div class="flex flex-wrap justify-center items-center gap-3 text-xs sm:text-sm font-medium">
                        <div class="flex items-center gap-1.5 text-gray-300 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                            <i class="ph-fill ph-code text-brand-gold"></i> Divy C. <span class="text-gray-500">Frontend</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-300 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                            <i class="ph-fill ph-database text-blue-400"></i> Dhruv P. <span class="text-gray-500">Backend</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-300 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                            <i class="ph-fill ph-file-text text-emerald-400"></i> Jay G. <span class="text-gray-500">Docs</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-300 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                            <i class="ph-fill ph-check-circle text-purple-400"></i> Naivedi B. <span class="text-gray-500">Valid</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 tracking-wider uppercase">&copy; 2026 OptiFleet | All Rights Reserved.</p>
                </div>
            </footer>
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