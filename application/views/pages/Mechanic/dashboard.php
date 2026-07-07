<div class="space-y-6 max-w-5xl mx-auto">
    <div class="border-b border-slate-200 pb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-400 tracking-tight">Dashboard Terminal Mekanik</h1>
            <p class="text-[11px] text-slate-400 mt-0.5">
                Tampilan:
                <?php echo ($current_view === 'all') ? '<span class="text-amber-500 font-bold">Semua kendaraan</span>' : '<span class="text-indigo-400 font-bold">Kendaraan Hari Ini</span>'; ?>
            </p>
        </div>


        <div>
            <?php if ($current_view === 'all'): ?>
                <a href="<?php echo base_url('Worker/dashboard'); ?>"
                    class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold px-3 py-1.5 text-xs rounded border border-slate-900 shadow-2xs transition cursor-pointer">
                    <span>📅</span> Tampilkan jadwal hari ini
                </a>
            <?php else: ?>
                <a href="<?php echo base_url('Worker/dashboard?view=all'); ?>"
                    class="inline-flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold px-3 py-1.5 text-xs rounded border border-amber-700 shadow-2xs transition cursor-pointer">
                    <span>🔍</span> Tampilkan seluruh jadwal
                </a>
            <?php endif; ?>
        </div>
    </div>


    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold">
                    <th class="p-4">Detail Kendaraan</th>
                    <th class="p-4">Status Saat Ini</th>
                    <th class="p-4">Sparepart yang digunakan</th>
                    <th class="p-4 text-right">Update Status Pengerjaan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($schedules)): ?>
                    <?php foreach ($schedules as $job): ?>

                        <tr
                            class="hover:bg-slate-50/50 transition <?php echo ($job->status_servis == 'Selesai' || $job->status_servis == 'Dibatalkan') ? 'bg-slate-50/30' : ''; ?>">


                            <td class="p-4">
                                <div class="font-bold text-slate-900">
                                    <?php echo $job->brand_kendaraan . ' ' . $job->model_kendaraan; ?>
                                </div>
                                <div class="text-[10px] font-mono text-indigo-600 font-bold mt-0.5"><?php echo $job->no_plat; ?>
                                    • client: <?php echo $job->nama_pelanggan; ?></div>
                                <div class="text-[10px] text-slate-400 mt-1">Active text trace updates: <span
                                        class="italic text-slate-600"><?php echo $job->detail_progres ? $job->detail_progres : 'No records logs.'; ?></span>
                                </div>
                            </td>


                            <td class="p-4">
                                <?php if ($job->status_servis == 'Selesai'): ?>
                                    <span
                                        class="inline-flex px-2 py-0.5 text-[10px] font-black uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full">Selesai</span>
                                <?php elseif ($job->status_servis == 'Dibatalkan'): ?>
                                    <span
                                        class="inline-flex px-2 py-0.5 text-[10px] font-black uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200 rounded-full">Dibatalkan</span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex px-2 py-0.5 text-[10px] font-black uppercase tracking-wide bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full"><?php echo $job->status_servis; ?></span>
                                <?php endif; ?>
                            </td>


                            <td class="p-4">

                                <?php if ($job->status_servis == 'Selesai' || $job->status_servis == 'Dibatalkan'): ?>
                                    <span class="text-[11px] font-medium text-slate-400 italic">Tidak bisa melihat stok, perbaikan
                                        sudah selesai</span>
                                <?php else: ?>
                                    <form action="<?php echo base_url('Worker/log_used_sparepart'); ?>" method="POST"
                                        class="flex gap-1.5">
                                        <input type="hidden" name="id_transaksi" value="<?php echo $job->id_transaksi; ?>">


                                        <input type="text" list="parts-catalog-<?php echo $job->id_transaksi; ?>"
                                            name="nama_part_display" placeholder="Cari Komponen" required
                                            class="border border-slate-300 rounded px-2 py-1 text-xs focus:outline-indigo-600 w-36 bg-white text-slate-900 font-medium">

                                        <datalist id="parts-catalog-<?php echo $job->id_transaksi; ?>">
                                            <?php foreach ($parts as $item): ?>

                                                <option value="<?php echo $item->nama_part; ?>">
                                                    (Stok: <?php echo $item->stok; ?> | Rp
                                                    <?php echo number_format($item->harga_jual, 0, ',', '.'); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </datalist>

                                        <input type="number" name="qty" placeholder="qty" required min="1"
                                            class="border border-slate-300 rounded text-center text-xs w-10 py-1 bg-white text-slate-900">
                                        <button type="submit"
                                            class="bg-slate-900 text-white font-bold px-2 rounded hover:bg-slate-800 transition cursor-pointer">Simpan</button>
                                    </form>
                                <?php endif; ?>
                            </td>


                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    <?php if ($job->status_servis == 'Selesai' || $job->status_servis == 'Dibatalkan'): ?>


                                        <a href="<?php echo base_url('Worker/undo_repair_status/' . $job->id_transaksi); ?>"
                                            onclick="return confirm('Apakah Anda ingin membatalkan status final dan kembali ke mode Pengerjaan?');"
                                            class="text-[11px] bg-amber-50 text-amber-700 border border-amber-200 font-bold px-3 py-1 rounded hover:bg-amber-100 transition shadow-2xs cursor-pointer inline-flex items-center gap-1"
                                            title="Revert status parameter back to operational workspace grids">
                                            <span class="text-xs">↺</span> Undo
                                        </a>

                                    <?php else: ?>


                                        <form action="<?php echo base_url('Worker/update_repair_progress'); ?>" method="POST"
                                            class="inline-flex gap-1">
                                            <input type="hidden" name="id_transaksi" value="<?php echo $job->id_transaksi; ?>">

                                            <input type="text" name="detail_progres" placeholder="Detail Perbaikan" required
                                                class="border border-slate-300 rounded px-2 py-1 text-[11px] focus:outline-indigo-600 w-32 bg-white text-slate-900 font-medium">

                                            <select name="status_servis"
                                                class="rounded border border-slate-300 px-1 py-1 text-[11px] bg-white text-slate-700 font-bold focus:outline-indigo-600">
                                                <option value="Inspeksi Awal" <?php echo ($job->status_servis == 'Inspeksi Awal') ? 'selected' : ''; ?>>Inspeksi Awal</option>
                                                <option value="Pengerjaan" <?php echo ($job->status_servis == 'Pengerjaan') ? 'selected' : ''; ?>>Pengerjaan</option>
                                                <option value="Selesai">Selesai</option>
                                                <option value="Dibatalkan">Dibatalkan</option>
                                            </select>

                                            <button type="submit"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-2 py-1 rounded transition cursor-pointer text-xs">Simpan</button>
                                        </form>

                                        <a href="<?php echo base_url('Worker/delay_assignment/' . $job->id_transaksi); ?>"
                                            class="text-[11px] bg-rose-50 text-rose-600 border border-rose-200 font-bold px-2 py-1 rounded hover:bg-rose-100 transition shadow-xs cursor-pointer"
                                            title="Tertunda hingga esok">Delay</a>

                                    <?php endif; ?>

                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
    function updateMaxQty(transaksiId) {
        const input = document.getElementById('part-input-' + transaksiId);
        const qtyInput = document.getElementById('qty-input-' + transaksiId);
        const datalist = document.getElementById('parts-catalog-' + transaksiId);

        if (!input || !qtyInput || !datalist) return;

        const val = input.value;
        const options = datalist.options;

        for (let i = 0; i < options.length; i++) {
            if (options[i].value === val) {
                const maxStok = options[i].getAttribute('data-stok');
                if (maxStok) {
                    qtyInput.setAttribute('max', maxStok);
                    return;
                }
            }
        }
        qtyInput.removeAttribute('max');
    }
</script>