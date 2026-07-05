
<div class="space-y-8 max-w-6xl mx-auto">
    <div class="flex border-b border-slate-200 pb-4 justify-between ">
        <h1 class="text-2xl font-black text-slate-400 tracking-tight">Dashboard Admin</h1>
        <button onclick="document.getElementById('adminPasswordModal').showModal()"
        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 text-xs rounded shadow-xs transition cursor-pointer">
    Ganti Password Admin
</button>
    </div>

    <!-- BAGIAN DISPLAY STATISTIK -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Pelanggan</span>
            <span class="text-3xl font-black text-slate-900 mt-1 block"><?php echo $total_customers; ?></span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Kendaraan yang
                tendaftar</span>
            <span class="text-3xl font-black text-slate-900 mt-1 block"><?php echo $total_vehicles; ?></span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Transaksi</span>
            <span class="text-3xl font-black text-slate-900 mt-1 block"><?php echo $total_services; ?></span>
        </div>
    </div>

    <!-- BAGIAN ADMIN -->
 
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- BAGIAN ADMIN TAMBAH MANAGER-->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs max-w-md lg:col-span-1">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Tambah Akun Manager</h2>

           
            <form action="<?php echo base_url('Admin/create_manager_process'); ?>" method="POST" class="space-y-4">

                <div>
                    <label class="block text-xs font-semibold text-slate-600">Nama Lengkap</label>
                    
                    <input type="text" name="name" value="<?php echo set_value('name'); ?>" required
                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-xs">
                    <?php if (form_error('name')): ?>
                        <p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('name'); ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600">Email</label>
                    
                    <input type="email" name="email" value="<?php echo set_value('email'); ?>" required
                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-xs">
                    <?php if (form_error('email')): ?>
                        <p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('email'); ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600">Password</label>
                 
                    <input type="password" name="password" required
                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-xs">
                    <?php if (form_error('password')): ?>
                        <p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('password'); ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit"
                    class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 rounded text-xs transition shadow-xs cursor-pointer">
                    Tambah Manager
                </button>

            </form>

        </div>
        <!-- BAGIAN ADMIN DETAIL SEMUA AKUN KECUALI ADMIN-->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden lg:col-span-2">
            <div class="p-4 bg-slate-50/50 border-b border-slate-200">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Staff & Pelanggan</h2>
            </div>
            <div class="max-h-120 overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-thumb-slate-200">
                <table class="w-full text-left border-collapse text-xs text-slate-900">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold sticky top-0 z-10">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nama Lengkap</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Tipe User</th>
                        <th class="p-4 text-right">Override</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (!empty($all_users)): ?>
                        <?php foreach ($all_users as $user_row): ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 font-mono font-bold text-indigo-600">#<?php echo $user_row->id; ?></td>
                                <td class="p-4 font-bold text-slate-900"><?php echo $user_row->name; ?></td>
                                <td class="p-4 text-slate-500"><?php echo $user_row->email; ?></td>
                                <td class="p-4">
                                    <?php if ($user_row->role_id == 4): ?>
                                        <span
                                            class="px-2 py-0.5 font-black uppercase text-[9px] tracking-wider rounded-full bg-purple-50 text-purple-700 border border-purple-200">Manager</span>
                                    <?php elseif ($user_row->role_id == 2): ?>
                                        <span
                                            class="px-2 py-0.5 font-black uppercase text-[9px] tracking-wider rounded-full bg-blue-50 text-blue-700 border border-blue-200">Mechanic</span>
                                    <?php else: ?>
                                        <span
                                            class="px-2 py-0.5 font-black uppercase text-[9px] tracking-wider rounded-full bg-slate-50 text-slate-600 border border-slate-200">Customer</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-right">
                                    
                                    <form action="<?php echo base_url('Admin/change_user_password_process'); ?>" method="POST"
                                        class="inline-flex gap-1.5 justify-end">
                                        <input type="hidden" name="id_user" value="<?php echo $user_row->id; ?>">
                                        <input type="password" name="new_password" placeholder="Ganti Password" required
                                            class="border border-slate-300 rounded px-2 py-1 text-xs bg-white text-slate-900 focus:outline-indigo-600 w-36 font-medium">
                                        <button type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin mengganti password untuk pengguna ini?');"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-1 rounded text-[10px] transition shadow-2xs cursor-pointer">
                                            Reset
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-4 text-center text-slate-400 italic bg-slate-50/20">Tidak ada user yang terdaftar</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>


</div>
<!-- BAGIAN DIALOG GANTI PASSWORD ADMIN -->
<dialog id="adminPasswordModal" 
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 backdrop:bg-slate-900/60 p-0 rounded-xl border border-slate-200 shadow-2xl max-w-sm w-full bg-white open:animate-in open:fade-in open:zoom-in-95 duration-200 focus:outline-none">
    
    <div class="p-6">
       
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
            <div>
                <h3 class="text-sm font-black text-slate-900 tracking-tight">Ganti Password Root Admin</h3>
            
            </div>
            <button type="button" onclick="document.getElementById('adminPasswordModal').close()" class="text-slate-400 hover:text-slate-600 font-bold text-xs p-1 cursor-pointer">✕</button>
        </div>

       
        <form action="<?php echo base_url('Admin/change_user_password_process'); ?>" method="POST" class="space-y-4 text-xs">
            
            <input type="hidden" name="id_user" value="<?php echo $current_admin_id; ?>">

            <div>
                <label class="block font-semibold text-slate-600">Password Baru</label>
                <input type="password" name="new_password" placeholder="Minimal 6 karakter" required 
                       class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 bg-white text-slate-900 font-medium">
            </div>

          
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100 mt-4">
                <button type="button" onclick="document.getElementById('adminPasswordModal').close()" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold px-3 py-1.5 rounded transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-1.5 rounded transition shadow-xs cursor-pointer">
                    Simpan Sandi
                </button>
            </div>
            
        </form>
    </div>
</dialog>
