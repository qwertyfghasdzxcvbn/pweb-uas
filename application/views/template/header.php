<nav class="bg-slate-900 border-b border-slate-800 text-slate-800 shadow-md">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <span class="text-xl font-black tracking-tight text-indigo-400">Demo</span>
            
        </div>
        
        <div class="flex items-center gap-6">
            <?php if($this->session->userdata('logged_in')): ?>
                <div class="flex items-center gap-4">
                    <span class="text-xs text-slate-400 font-medium">Hello, <b class="text-slate-200"><?php echo $this->session->userdata('name'); ?></b></span>
                    <a href="<?php echo base_url('auth/logout'); ?>" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-3 py-1.5 rounded transition shadow-xs">Sign Out</a>
                </div>
            <?php else: ?>
                <a href="<?php echo base_url('auth/process_login'); ?>" class="text-xs font-bold text-slate-50 bg-indigo-500 hover:text-black transition px-4 py-2 rounded border-slate-100">LOGIN</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
