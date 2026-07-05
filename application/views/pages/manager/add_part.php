<div class="max-w-md mx-auto mt-6">
    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
        
        <div class="mb-6">
            <h1 class="text-xl font-black text-slate-900 tracking-tight">
                <?php echo isset($is_edit) ? 'Ubah Data Suku Cadang' : 'Tambah Stok'; ?>
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                <?php echo isset($is_edit) ? 'Sesuaikan jumlah stok gudang dan harga unit Suku Cadang #'.$part->id_part : 'Tambahkan Stok baru Atau Jumlah Stok yang ada'; ?>
            </p>
        </div>

        <form action="<?php echo isset($is_edit) ? base_url('Manager/update_part_process') : base_url('Manager/add_part_process'); ?>" method="POST" class="space-y-4">
            
            <?php if(isset($is_edit)): ?>
                <input type="hidden" name="id_part" value="<?php echo $part->id_part; ?>">
            <?php endif; ?>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Nama Sparepart</label>
                <input type="text" name="nama_part" placeholder="Rem Nmax" required 
                       value="<?php echo set_value('nama_part', isset($is_edit) ? $part->nama_part : ''); ?>" 
                       class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm bg-white text-slate-900 font-medium">
                <?php echo form_error('nama_part'); ?>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">
                        <?php echo isset($is_edit) ? 'Jumlah Stok' : 'Stok Awal'; ?>
                    </label>
                    <input type="number" name="stok" placeholder="0" required 
                           value="<?php echo set_value('stok', isset($is_edit) ? $part->stok : ''); ?>" 
                           class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm bg-white text-slate-900 font-medium">
                    <?php echo form_error('stok'); ?>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Harga Jual</label>
                    <input type="number" name="harga_jual" placeholder="Rp" required 
                           value="<?php echo set_value('harga_jual', isset($is_edit) ? $part->harga_jual : ''); ?>" 
                           class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm bg-white text-slate-900 font-medium">
                    <?php echo form_error('harga_jual'); ?>
                </div>
            </div>

            <div class="flex items-center space-x-3 pt-2">
                <?php if(isset($is_edit)): ?>
                    <a href="<?php echo base_url('Manager/dashboard'); ?>" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2 text-center rounded-md transition text-sm">
                       Batal
                    </a>
                <?php endif; ?>
                <button type="submit" class="<?php echo isset($is_edit) ? 'w-2/3' : 'w-full'; ?> bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-md transition text-sm shadow cursor-pointer">
                   Simpan
                </button>
            </div>
            
        </form>

    </div>
</div>
