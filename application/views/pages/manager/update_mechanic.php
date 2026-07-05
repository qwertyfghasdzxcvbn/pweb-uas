<div class="max-w-md mx-auto mt-6">
    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
        
        <div class="mb-6">
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Update profil mekanik</h1>
            <p class="text-xs text-slate-500 mt-1">ID: #<?php echo $worker->id_mekanik; ?></p>
        </div>

       
        <form action="<?php echo base_url('Manager/update_mechanic_process'); ?>" method="POST" class="space-y-4">
           
            <input type="hidden" name="id_mekanik" value="<?php echo $worker->id_mekanik; ?>">
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Nama Lengkap</label>
                <input type="text" name="nama_mekanik" required 
                       value="<?php echo set_value('nama_mekanik', $worker->nama_mekanik); ?>" 
                       class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm bg-white text-slate-900 font-medium">
                <?php echo form_error('nama_mekanik'); ?>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Keahlian</label>
                <input type="text" name="keahlian" required 
                       value="<?php echo set_value('keahlian', $worker->keahlian); ?>" 
                       class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm bg-white text-slate-900 font-medium">
                <?php echo form_error('keahlian'); ?>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Stall Availability State</label>
                <select name="status_ketersediaan" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm bg-white text-slate-900 font-medium">
                    <option value="Tersedia" <?php echo ($worker->status_ketersediaan == 'Tersedia') ? 'selected' : ''; ?>>Tersedia </option>
                    <option value="Tidak Tersedia" <?php echo ($worker->status_ketersediaan == 'Tidak Tersedia') ? 'selected' : ''; ?>>Tidak Tersedia </option>
                    <option value="Nonaktif" <?php echo ($worker->status_ketersediaan == 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                </select>
                <?php echo form_error('status_ketersediaan'); ?>
            </div>
            <div class="flex items-center space-x-3 pt-2">
                <a href="<?php echo base_url('Manager/dashboard'); ?>" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2 text-center rounded-md transition text-sm">
                   Batal
                </a>
                <button type="submit" class="w-2/3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-md transition text-sm shadow cursor-pointer">
                   Simpan Perubahan
                </button>
            </div>
            
        </form>

    </div>
</div>
