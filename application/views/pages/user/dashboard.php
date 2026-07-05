<div class="max-w-4xl mx-auto space-y-8">


    <div class="flex flex-col sm:flex-row justify-between sm:items-center border-b border-slate-200 pb-4 gap-4">
        <div>
            <h1 class="text-2xl text-slate-300 font-bold tracking-tight">Dashboard Pelanggan</h1>
            <p class="text-xs text-slate-300 mt-1">Tambah Kendaraan dan Reservasi Perbaikan</p>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-center">

            <a href="<?php echo base_url('User/add_vehicle_process'); ?>"
                class="bg-white border border-slate-300 text-slate-700 text-xs font-bold px-3 py-2 rounded-lg hover:bg-slate-50 transition shadow-xs">
                Tambah Kendaraan
            </a>
            <a href="<?php echo base_url('User/book_service_process'); ?>"
                class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-3 py-2 rounded-lg transition shadow-xs">
                Reservasi
            </a>
        </div>
    </div>


    <?php if ($this->session->flashdata('success')): ?>
        <div
            class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-lg shadow-xs">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="p-3 bg-rose-200 border border-rose-200 text-rose-600 text-xs font-semibold rounded-lg shadow-xs">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>


    <div class="space-y-4">
        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Perbaikan Sedang Berlangsung</h2>

        <?php if (!empty($services)): ?>
            <?php foreach ($services as $item): ?>
                <div
                    class="bg-slate-100 p-6 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between md:items-center gap-6 transition hover:shadow-sm">


                    <div class="space-y-1 flex-1">
                        <div class="flex items-baseline gap-2">
                            <h3 class="font-bold text-slate-900 text-base">
                                <?php echo $item->brand_kendaraan . ', ' . $item->model_kendaraan; ?></h3>
                            <span class="text-xs text-indigo-600 font-mono font-bold"><?php echo $item->no_plat; ?></span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Keluhan Awal: <span class="text-slate-700 font-medium"><?php echo $item->keluhan_awal; ?></span>
                        </p>
                        <p class="text-xs text-slate-500 pt-1">
                            Pesan Mekanik: <span
                                class="italic font-bold text-indigo-900"><?php echo $item->detail_progres ? $item->detail_progres : 'Awaiting inspection crew assignment.'; ?></span>
                        </p>
                        <div class="pt-2 text-xs font-semibold text-slate-700">
                            Total Pembayaran: <span class="text-slate-900 font-black">Rp
                                <?php echo number_format($item->total_biaya, 0, ',', '.'); ?></span>
                        </div>
                    </div>


                    <div class="text-left md:text-right space-y-3 shrink-0 self-start md:self-center">
                        <div>
                            <?php
                            $status_badge = "bg-amber-50 text-amber-700 border-amber-200";
                            if ($item->status_servis === 'Work') {
                                $status_badge = "bg-indigo-50 text-indigo-700 border-indigo-200";
                            } elseif ($item->status_servis === 'Finished') {
                                $status_badge = "bg-emerald-50 text-emerald-700 border-emerald-200";
                            } elseif ($item->status_servis === 'Canceled') {
                                $status_badge = "bg-rose-50 text-rose-700 border-rose-200";
                            }
                            ?>
                            <span
                                class="inline-block text-xs font-black uppercase tracking-wide border rounded-full px-2.5 py-0.5 <?php echo $status_badge; ?>">
                                <?php echo $item->status_servis ?>
                            </span>
                        </div>


                        <div>
                            <?php if ($item->status_servis === 'Selesai' && $item->status_pembayaran === 'Belum Lunas'): ?>
                                <form action="<?php echo base_url('user/submit_payment_proof'); ?>" method="POST"
                                    class="flex items-center gap-2 md:justify-end">
                                    <input type="hidden" name="id_transaksi" value="<?php echo $item->id_transaksi; ?>">
                                    <input type="text" name="proof_ref" placeholder="Payment Ref Code..." required
                                        class="border border-slate-300 rounded px-2 py-1 text-xs focus:outline-indigo-600 w-36 placeholder:text-slate-300 font-medium">
                                    <button type="submit"
                                        class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-3 py-1 rounded text-xs transition shadow-xs cursor-pointer">
                                        Kirim Bukti Transfer/Pembayaran
                                    </button>
                                </form>
                            <?php else: ?>
                                <p
                                    class="text-[11px] font-semibold text-slate-400 bg-slate-50 border border-slate-100 p-2 rounded-lg inline-block">
                                    <?php
                                    if ($item->status_servis !== 'Selesai') {
                                        echo '🔒 Invoice Pembayaran Akan Terbuka Setelah Perbaikan Selesai.';
                                    } elseif ($item->status_pembayaran === 'Menunggu Verifikasi') {
                                        echo 'Bukti Telah Dikirim.';
                                    } else {
                                        echo 'Transaksi Selesai.';
                                    }
                                    ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>

            <div
                class="text-center p-12 bg-white rounded-xl border border-dashed border-slate-300 text-slate-400 font-medium shadow-2xs">
                Belum ada perbaikan yang sedang berlangsung.
            </div>
        <?php endif; ?>
    </div>
</div>