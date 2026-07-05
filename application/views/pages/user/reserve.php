<div class="max-w-xl mx-auto mt-6">
    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-xs">

        <div class="mb-6">
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Reservasi Jadwal</h1>
            <p class="text-xs text-slate-500 mt-1">Pilih jadwal reservasi</p>
        </div>


        <form action="<?php echo base_url('user/book_service_process'); ?>" method="POST" class="space-y-4">

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Pilih Kendaraan </label>
                <select name="no_plat"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm bg-white font-medium text-slate-800">
                    <option value="">-- Pilih Kendaraan --</option>

                    <?php if (!empty($vehicles)): ?>
                        <?php foreach ($vehicles as $car): ?>
                            <option value="<?php echo $car->no_plat; ?>" <?php echo set_select('no_plat', $car->no_plat); ?>>
                                <?php echo $car->brand_kendaraan . ' ' . $car->model_kendaraan; ?>
                                (<?php echo $car->no_plat; ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?php if (form_error('no_plat')): ?>
                    <p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('no_plat'); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Pilih Jadwal</label>

                <input type="date" name="tanggal_servis" min="<?php echo date('Y-m-d'); ?>"
                    max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm bg-white text-slate-900 font-medium">
                <?php if (form_error('tanggal_servis')): ?>
                    <p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('tanggal_servis'); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Keluhan Awal</label>
                <textarea name="keluhan" placeholder="Deskripsikan keluhan awal"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm h-28"><?php echo set_value('keluhan'); ?></textarea>
                <?php if (form_error('keluhan')): ?>
                    <p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('keluhan'); ?></p>
                <?php endif; ?>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-md transition text-sm shadow cursor-pointer">
                Lakukan Reservasi
            </button>

        </form>

    </div>
</div>