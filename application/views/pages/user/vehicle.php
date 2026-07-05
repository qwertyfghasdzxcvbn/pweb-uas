<div class="max-w-xl mx-auto mt-6">
    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
        <div class="mb-4">
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Tambahkan Kendaraan Anda</h1>
        
        </div>

        <form action="<?php echo base_url('User/add_vehicle_process'); ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Plat Nomor</label>
                <input type="text" name="no_plat" value="<?php echo set_value('no_plat'); ?>" placeholder="DR 1234 RD" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm uppercase">
                <?php if(form_error('no_plat')): ?><p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('no_plat'); ?></p><?php endif; ?>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Brand Kendaraan</label>
                    <input type="text" name="brand" value="<?php echo set_value('brand'); ?>" placeholder="Honda, Yamaha" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">model</label>
                    <input type="text" name="model" value="<?php echo set_value('model'); ?>" placeholder="Civic, Vario, Nmax" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Tahun Rilis</label>
                <input type="number" name="year" value="<?php echo set_value('year'); ?>" placeholder="2023" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm">
                <?php if(form_error('year')): ?><p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('year'); ?></p><?php endif; ?>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-md transition text-sm shadow cursor-pointer">
        Masukkan Kendaraan
    </button>
        </form>
    </div>
</div>
