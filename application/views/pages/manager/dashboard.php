<div class="space-y-8 max-w-6xl mx-auto">
   
    <div class="border-b border-slate-200 pb-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-300 tracking-tight font-poppins">Dashboard Manager</h1>
            <p class="text-xs text-slate-500 mt-0.5 ">Kontrol Operational</p>
        </div>
        <a href="<?php echo base_url('Manager/add_part_process');  ?>" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2 rounded-lg transition shadow-xs cursor-pointer">Tambah Sparepart</a>
    </div>
    
    <?php if($this->session->flashdata('success')): ?>
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-lg shadow-2xs">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold rounded-lg shadow-2xs">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
       
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs ">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Tambah Mekanik</h2>
            <form action="<?php echo base_url('Manager/create_mechanic_process'); ?>" method="POST" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600">Nama Lengkap</label>
                    <input type="text" name="name" value="<?php echo set_value('name'); ?>" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-xs bg-white">
                    <?php echo form_error('name'); ?>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600">Email</label>
                    <input type="email" name="email" value="<?php echo set_value('email'); ?>" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-xs bg-white">
                    <?php echo form_error('email'); ?>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600">Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-xs bg-white">
                    <?php echo form_error('password'); ?>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600">Keahlian</label>
                    <input type="text" name="keahlian" value="<?php echo set_value('keahlian'); ?>" placeholder="Spesialis Nugas" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-xs bg-white">
                    <?php echo form_error('keahlian'); ?>
                </div>
                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 rounded text-xs transition shadow-xs cursor-pointer">Submit Mekanik</button>
            </form>
        </div>

       
        <div class="lg:col-span-2 space-y-6">
           
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Verifikasi Pembayaran Pelanggan</h2>
                </div>
                <table class="w-full text-left border-collapse text-xs ">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-slate-500 font-semibold">
                            <th class="p-3">Pelanggan/Kendaraan</th>
                            <th class="p-3">Total Biaya</th>
                            <th class="p-3">Kode Transaksi</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(!empty($pending_payments)): ?>
                            <?php foreach($pending_payments as $payment): ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3 text-slate-900 font-medium">
                                    <div class="font-bold text-slate-800"><?php echo $payment->nama_pelanggan; ?></div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5"><?php echo $payment->brand_kendaraan . ' ' . $payment->model_kendaraan; ?> (<?php echo $payment->no_plat; ?>)</div>
                                </td>
                                <td class="p-3 font-semibold text-slate-900">
                                    Rp <?php echo number_format($payment->total_biaya, 0, ',', '.'); ?>
                                </td>
                                <td class="p-3 font-mono font-bold text-indigo-600 bg-indigo-50/50 rounded border border-indigo-100/60 px-2 py-0.5">
                                    <?php echo $payment->bukti_pembayaran; ?>
                                </td>
                                <td class="p-3 text-right">
                                    <a href="<?php echo base_url('Manager/verify_payment_action/'.$payment->id_transaksi); ?>" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[11px] px-3 py-1.5 rounded transition shadow-xs cursor-pointer">
                                        Verifikasi dan Selesaikan Transaksi
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="p-6 text-center text-slate-400 font-medium">Tidak ada transaksi</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
           
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50"><h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Spare Parts Warehouse Inventory Status</h2></div>
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-slate-500 font-semibold">
                            <th class="p-3">ID</th>
                            <th class="p-3">Nama Sparepart</th>
                            <th class="p-3">Stok</th>
                            <th class="p-3 text-right">Harga Jual/Unit</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(!empty($parts)): ?>
                            <?php foreach($parts as $item): ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3 font-mono text-slate-400 font-bold">#<?php echo $item->id_part; ?></td>
                                <td class="p-3 text-slate-900 font-medium"><?php echo $item->nama_part; ?></td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold <?php echo ($item->stok < 15) ? 'bg-rose-50 text-rose-600  border-rose-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100'; ?>">
                                     Tersisa  <?php echo $item->stok; ?> 
                                    </span>
                                </td>
                                <td class="p-3 text-right font-medium text-slate-900">Rp <?php echo number_format($item->harga_jual, 0, ',', '.'); ?></td>
                                 <td class="p-3 text-right">
                                    <a href="<?php echo base_url('Manager/delete_part_action/' . $item->id_part); ?>" class="items-center bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-bold text-[11px] px-3 py-1 rounded transition shadow-2xs cursor-pointer">Hapus</a>
                                    <a href="<?php echo base_url('Manager/update_part_view/' . $item->id_part); ?>" class="items-center bg-emerald-50 hover:bg-emerald-100 text-emerald-500 border border-emerald-200 font-bold text-[11px] px-3 py-1 rounded transition shadow-2xs cursor-pointer">Update</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="p-4 text-center text-slate-400">Tidak ada sparepart tersedia</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

          
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50"><h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mekanik Terdaftar</h2></div>
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-slate-500 font-semibold">
                            <th class="p-3">ID Mekanik</th>
                            <th class="p-3">Nama Lengkap</th>
                            <th class="p-3">Spesialis</th>
                            <th class="p-3 text-right">Ketersediaan</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(!empty($mechanics)): ?>
                            <?php foreach($mechanics as $tech): ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3 font-mono text-slate-400 font-bold">#<?php echo $tech->id_mekanik; ?></td>
                                <td class="p-3 text-slate-900 font-bold"><?php echo $tech->nama_mekanik; ?></td>
                                <td class="p-3 text-slate-600 font-medium"><?php echo $tech->keahlian; ?></td>
                                <td class="p-3 text-right">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border bg-emerald-50 text-emerald-700 border-emerald-200">
                                        <?php echo $tech->status_ketersediaan; ?>
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <a href="<?php echo base_url('Manager/update_mechanic_view/' . $tech->id_mekanik); ?>" class="items-center bg-emerald-50 hover:bg-emerald-100 text-emerald-500 border border-emerald-200 font-bold text-[11px] px-3 py-1 rounded transition shadow-2xs cursor-pointer">Update</a></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="p-4 text-center text-slate-400">Tidak ada mekanik yang terdaftar</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden mt-8">
        <div class="p-4 bg-slate-50/50 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Transaksi</h2>
                <p class="text-[10px] text-slate-400 mt-0.5">Daftar Transaksi Real Time</p>
            </div>
            <span class="px-2 py-0.5 text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full">
                Total: <?php echo count($all_transactions); ?> Record(s)
            </span>
        </div>

        <div class="max-h-100 overflow-y-auto overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse text-xs text-slate-900 table-auto">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold sticky top-0 z-10">
                        <th class="p-4 bg-slate-50">ID Transaksi</th>
                        <th class="p-4 bg-slate-50">Detail Pelanggan</th>
                        <th class="p-4 bg-slate-50">Mekanik</th>
                        <th class="p-4 bg-slate-50">Tanggal</th>
                        <th class="p-4 bg-slate-50">Status Perbaikan</th>
                        <th class="p-4 bg-slate-50">Status Pembayaran</th>
                        <th class="p-4 text-right bg-slate-50">Total Transaksi (Rp)</th>
                        
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(!empty($all_transactions)): ?>
                        <?php foreach($all_transactions as $tx): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-mono font-bold text-indigo-600">#TX-<?php echo $tx->id_transaksi; ?></td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900"><?php echo $tx->brand_kendaraan . ' ' . $tx->model_kendaraan; ?></div>
                                <div class="text-[10px] text-slate-500 font-mono mt-0.5"><?php echo $tx->no_plat; ?> • Client: <?php echo $tx->nama_pelanggan; ?></div>
                                <div class="text-[10px] text-indigo-600 font-medium mt-1">
                                    Alamat: <span class="italic text-slate-600"><?php echo $tx->alamat ? $tx->alamat : 'Belum mengisi alamat.'; ?></span>
                                </div>
                            </td>
                            <td class="p-4 font-medium text-slate-700">
                                <?php echo !empty($tx->nama_mekanik) ? $tx->nama_mekanik : '<span class="text-slate-400 italic">Unassigned</span>'; ?>
                            </td>
                            <td class="p-4 text-slate-500 font-medium"><?php echo date('d M Y', strtotime($tx->tanggal_servis)); ?></td>
                            <td class="p-4">
                                <?php if($tx->status_servis == 'Selesai'): ?>
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>
                                <?php elseif($tx->status_servis == 'Dibatalkan'): ?>
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-full bg-rose-50 text-rose-700 border border-rose-200">Dibatalkan</span>
                                <?php elseif($tx->status_servis == 'Tertunda'): ?>
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-full bg-amber-50 text-amber-700 border border-amber-200">Tertunda</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200"><?php echo $tx->status_servis; ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <?php if($tx->status_pembayaran == 'Lunas'): ?>
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-full bg-emerald-600 text-white shadow-2xs">Paid (Lunas)</span>
                                <?php elseif($tx->status_pembayaran == 'Menunggu Verifikasi'): ?>
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-full bg-amber-50 text-amber-800 border border-amber-300">Verifying</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-full bg-slate-100 text-slate-500 border border-slate-200">Unpaid</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-right font-mono font-bold text-slate-900">
                                Rp <?php echo number_format($tx->total_biaya, 0, ',', '.'); ?>
                            </td>
                           
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="p-4 text-center text-slate-400 italic bg-slate-50/20">Tidak ada detail transaksi</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
