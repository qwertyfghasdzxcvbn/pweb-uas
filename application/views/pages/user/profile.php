<div class="max-w-md mx-auto mt-10">
    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
        
     
        <div class="mb-6 text-center">
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Selamat Datang, <?php echo $this->session->userdata('name'); ?>!</h1>
            <p class="text-xs text-slate-500 mt-1">Masukkan detail profil anda terlebih dahulu</p>
        </div>

       
        <form action="<?php echo base_url('User/complete_profile_action'); ?>" method="POST" class="space-y-4">
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Nomor Hp</label>
             
                <input type="text" name="no_telepon" value="<?php echo set_value('no_telepon'); ?>" placeholder="08123456789" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm">
                <?php if(form_error('no_telepon')): ?>
                    <p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('no_telepon'); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Alamat Rumah</label>
                <textarea name="alamat" placeholder="Masukkan alamat rumah lengkap anda" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm h-24"><?php echo set_value('alamat'); ?></textarea>
                <?php if(form_error('alamat')): ?>
                    <p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('alamat'); ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-md transition text-sm shadow cursor-pointer">
              Simpan Profil
            </button>
            
        </form>

    </div>
</div>
