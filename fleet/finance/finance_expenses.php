<?php
// ================= BACKEND PLACEHOLDER =================

// 1. Verify finance user session
// session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'finance') { header("Location: login.php"); exit; }

// 2. Fetch expense records
// 3. Handle new expense form submission
// 4. Insert fuel or maintenance expense into database
// 5. Filter expenses by type/date/vehicle

// =======================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OptiFleet | Expense Management</title>
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

        .glass-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%);
            border: 1px solid rgba(255, 255, 255, 0.05); transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .glass-card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px -10px rgba(212, 175, 55, 0.15); border-color: rgba(212, 175, 55, 0.2); }

        /* Premium Input Styles */
        .premium-input {
            width: 100%; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem; padding: 0.6rem 1rem; color: white; font-size: 0.9rem;
            transition: all 0.3s ease; outline: none;
        }
        .premium-input:focus { border-color: #D4AF37; background: rgba(255, 255, 255, 0.05); box-shadow: 0 0 15px rgba(212, 175, 55, 0.15); }
        select.premium-input option { background-color: #0A0A0B; color: white; }

        /* Ambient Glows */
        .ambient-glow { position: fixed; width: 600px; height: 600px; background: radial-gradient(circle, rgba(212, 175, 55, 0.06) 0%, transparent 70%); border-radius: 50%; pointer-events: none; z-index: 0; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(212, 175, 55, 0.4); }

        /* Button Styling */
        .btn-premium { background: linear-gradient(135deg, #D4AF37 0%, #AA8C2C 100%); color: #050505; font-weight: 600; transition: all 0.3s ease; border: none; }
        .btn-premium:hover { box-shadow: 0 0 15px rgba(212, 175, 55, 0.4); }
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
                <i class="ph ph-bank text-black text-xl font-bold"></i>
            </div>
            <span class="text-xl font-semibold tracking-wide text-white">
                Opti<span class="text-brand-gold">Fleet</span>
            </span>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <a href="finance_dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph-fill ph-squares-four text-xl"></i><span class="font-medium">Dashboard</span>
            </a>
            <!-- Active Menu Item -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand-gold/10 text-brand-gold border border-brand-gold/20 transition-all">
                <i class="ph-fill ph-receipt text-xl"></i><span class="font-medium">Expenses</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-file-text text-xl"></i><span class="font-medium">Reports</span>
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
            <h1 class="text-2xl font-semibold">Finance Dashboard</h1>
            
            <div class="flex items-center gap-6">
                <!-- Search -->
                <div class="relative hidden md:block">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Search vehicle, trip or expense..." class="bg-black/20 border border-white/10 rounded-full py-2 pl-10 pr-4 text-sm text-white focus:outline-none focus:border-brand-gold w-72 transition-colors">
                </div>

                <!-- Profile Actions -->
                <div class="flex items-center gap-4 pl-6 border-l border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-white">David Stern</p>
                            <p class="text-xs text-brand-gold">Financial Analyst</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-brand-gold/30">
                            <i class="ph-fill ph-chart-pie text-xl text-brand-gold"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Scrollable Dashboard Body -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8 pb-24">
            
            <!-- Page Title -->
            <div>
                <h2 class="text-3xl font-semibold text-white mb-1">Expense Management</h2>
                <p class="text-gray-400 text-sm">Track fuel and maintenance expenses across the fleet.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Col 1 & 2: Form and Cards -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Add Expense Form Card -->
                    <section class="glass-panel rounded-3xl p-6 relative">
                        <div class="flex items-center gap-3 mb-6">
                            <i class="ph-fill ph-plus-circle text-brand-gold text-2xl"></i>
                            <h3 class="text-xl font-semibold text-white">Add New Expense</h3>
                        </div>

                        <form id="expenseForm" onsubmit="handleExpense(event)" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs text-gray-400 mb-1.5 ml-1">Date <span class="text-status-danger">*</span></label>
                                <input type="date" id="expDate" class="premium-input" required>
                                <p id="err-date" class="hidden text-[10px] text-status-danger mt-1 ml-1"></p>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 mb-1.5 ml-1">Vehicle ID / Name <span class="text-status-danger">*</span></label>
                                <select id="expVehicle" class="premium-input" required>
                                    <option value="">Select Vehicle</option>
                                    <option value="Van-05">Van-05</option>
                                    <option value="Truck-02">Truck-02</option>
                                    <option value="Bike-11">Bike-11</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 mb-1.5 ml-1">Trip ID (Optional)</label>
                                <input type="text" id="expTrip" class="premium-input" placeholder="e.g. #TRP-9021">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 mb-1.5 ml-1">Expense Type <span class="text-status-danger">*</span></label>
                                <select id="expType" class="premium-input" required>
                                    <option value="Fuel">Fuel</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 mb-1.5 ml-1">Amount <span class="text-status-danger">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">₹</span>
                                    <input type="number" id="expAmount" class="premium-input pl-8" placeholder="0.00" min="0.01" step="0.01" required>
                                </div>
                                <p id="err-amount" class="hidden text-[10px] text-status-danger mt-1 ml-1"></p>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 mb-1.5 ml-1">Description (Optional)</label>
                                <input type="text" id="expDesc" class="premium-input" placeholder="Details...">
                            </div>

                            <div class="md:col-span-2 flex justify-end gap-3 mt-2">
                                <button type="reset" class="btn-outline px-6 py-2.5 rounded-xl text-sm font-medium">Reset</button>
                                <button type="submit" class="btn-premium px-8 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
                                    <i class="ph-bold ph-check"></i> Add Expense
                                </button>
                            </div>
                        </form>
                    </section>
                </div>

                <!-- Col 3: Summary Cards -->
                <div class="space-y-4">
                    <div class="glass-card rounded-2xl p-5 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-2">
                            <div class="w-10 h-10 rounded-xl bg-brand-gold/10 flex items-center justify-center border border-brand-gold/20">
                                <i class="ph ph-gas-can text-xl text-brand-gold"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-medium mb-1">Fuel Today</p>
                        <h3 class="text-2xl font-bold text-white">₹12,450</h3>
                    </div>

                    <div class="glass-card rounded-2xl p-5 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-2">
                            <div class="w-10 h-10 rounded-xl bg-status-info/10 flex items-center justify-center border border-status-info/20">
                                <i class="ph ph-wrench text-xl text-status-info"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-medium mb-1">Maintenance (Month)</p>
                        <h3 class="text-2xl font-bold text-white">₹1,12,000</h3>
                    </div>

                    <div class="glass-card rounded-2xl p-5 relative overflow-hidden group bg-brand-gold/5 border-brand-gold/20">
                        <div class="flex justify-between items-start mb-2">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                                <i class="ph ph-calculator text-xl text-gray-300"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-medium mb-1">Total Expenses</p>
                        <h3 class="text-2xl font-bold text-white">₹4,96,200</h3>
                    </div>
                </div>
            </div>

            <!-- Expense List Table -->
            <section class="glass-panel rounded-3xl p-6 relative flex flex-col">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 shrink-0">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-list-bullets text-brand-gold text-2xl"></i>
                        <h3 class="text-xl font-semibold text-white">Transaction Logs</h3>
                    </div>
                    
                    <div class="flex gap-3 w-full sm:w-auto">
                        <div class="relative flex-1 sm:w-64">
                            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" placeholder="Filter log..." class="premium-input pl-10 text-sm w-full">
                        </div>
                        <select class="premium-input w-32 text-sm text-gray-300">
                            <option value="all">All Types</option>
                            <option value="fuel">Fuel</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-white/10">
                                <th class="pb-4 pl-4">Date</th>
                                <th class="pb-4">Vehicle</th>
                                <th class="pb-4">Trip ID</th>
                                <th class="pb-4 text-center">Type</th>
                                <th class="pb-4 text-center">Amount</th>
                                <th class="pb-4 pr-4">Description</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white/5">
                            <!-- Fuel Row -->
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-4 text-gray-400">21 Feb 2026</td>
                                <td class="py-4 text-white font-medium">Van-05</td>
                                <td class="py-4 font-mono text-xs text-gray-500">#TRP-9021</td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-brand-gold/10 text-brand-gold border border-brand-gold/20 uppercase tracking-wider">Fuel</span>
                                </td>
                                <td class="py-4 text-center font-bold text-white">₹4,200.00</td>
                                <td class="py-4 pr-4 text-gray-500 italic">Full tank refill - Shell St.</td>
                            </tr>
                            
                            <!-- Maintenance Row (Light Orange Highlight) -->
                            <tr class="bg-status-warning/5 hover:bg-status-warning/10 transition-colors border-l-2 border-l-status-warning/40">
                                <td class="py-4 pl-4 text-gray-400">20 Feb 2026</td>
                                <td class="py-4 text-white font-medium">Truck-02</td>
                                <td class="py-4 font-mono text-xs text-gray-500">#MNT-44201</td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-status-info/10 text-status-info border border-status-info/20 uppercase tracking-wider">Maintenance</span>
                                </td>
                                <td class="py-4 text-center font-bold text-white">₹18,500.00</td>
                                <td class="py-4 pr-4 text-gray-500 italic">Brake pad replacement</td>
                            </tr>

                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-4 text-gray-400">20 Feb 2026</td>
                                <td class="py-4 text-white font-medium">Bike-11</td>
                                <td class="py-4 font-mono text-xs text-gray-500">#TRP-9018</td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-brand-gold/10 text-brand-gold border border-brand-gold/20 uppercase tracking-wider">Fuel</span>
                                </td>
                                <td class="py-4 text-center font-bold text-white">₹320.00</td>
                                <td class="py-4 pr-4 text-gray-500 italic">Petrol top-up</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination UI Only -->
                <div class="mt-6 flex justify-between items-center text-[10px] text-gray-500 font-bold uppercase tracking-widest px-4">
                    <span>Transaction History</span>
                    <div class="flex gap-2">
                        <button class="w-8 h-8 rounded bg-white/5 flex items-center justify-center opacity-50"><i class="ph ph-caret-left"></i></button>
                        <button class="w-8 h-8 rounded bg-brand-gold/20 flex items-center justify-center text-brand-gold border border-brand-gold/20">1</button>
                        <button class="w-8 h-8 rounded bg-white/5 flex items-center justify-center hover:bg-white/10 transition-colors"><i class="ph ph-caret-right"></i></button>
                    </div>
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
                this.x += this.vx; this.y += this.vy;
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
            }
            requestAnimationFrame(animate);
        }
        animate();

        // --- Frontend Validation Logic ---
        function handleExpense(event) {
            event.preventDefault();
            const amount = document.getElementById('expAmount');
            const date = document.getElementById('expDate');
            const submitBtn = event.target.querySelector('button[type="submit"]');

            let isValid = true;

            if (!date.value) {
                isValid = false;
                date.classList.add('border-status-danger');
            } else {
                date.classList.remove('border-status-danger');
            }

            if (parseFloat(amount.value) <= 0 || isNaN(parseFloat(amount.value))) {
                isValid = false;
                amount.classList.add('border-status-danger');
            } else {
                amount.classList.remove('border-status-danger');
            }

            if (isValid) {
                submitBtn.innerHTML = '<i class="ph ph-spinner animate-spin"></i> Processing...';
                submitBtn.disabled = true;

                // Simulate DB Interaction
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="ph ph-check-circle"></i> Expense Added';
                    submitBtn.style.background = '#22c55e';
                    submitBtn.style.color = '#fff';

                    setTimeout(() => {
                        event.target.reset();
                        submitBtn.innerHTML = '<i class="ph-bold ph-check"></i> Add Expense';
                        submitBtn.style = '';
                        submitBtn.disabled = false;
                    }, 2000);
                }, 1000);
            }
        }
    </script>
</body>
</html>