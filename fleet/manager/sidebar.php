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
            <!-- Active State -->
            <a href="manager_dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand-gold/10 text-brand-gold border border-brand-gold/20 transition-all">
                <i class="ph-fill ph-squares-four text-xl"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="manager_vehicle_registry.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-truck text-xl"></i>
                <span class="font-medium">Vehicle Registry</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-users text-xl"></i>
                <span class="font-medium">Drivers</span>
            </a>

            <!-- ADDED REGISTRATION LINK -->
            <a href="manager_registration.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="ph ph-user-plus text-xl"></i>
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
