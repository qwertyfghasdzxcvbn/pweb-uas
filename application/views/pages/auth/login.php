<div class="flex min-h-[75vh] flex-col justify-center px-6 py-12 lg:px-8">
  <div class="mt-4 sm:mx-auto sm:w-full sm:max-w-sm bg-white p-8 rounded-xl shadow-xs border border-slate-200">
    
   
    <div class="text-center mb-6">
        <h2 class="text-xl font-black tracking-tight text-slate-900">LOGIN</h2>
        <p class="text-xs text-slate-400 mt-1">Login menggunakan Email dan Password Anda.</p>
    </div>
    
  
    <?php if($this->session->flashdata('error')): ?>
        <div class="mb-4 p-3 bg-rose-50 text-rose-600 border border-rose-200 rounded-lg text-xs font-semibold">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

   
    <?php if($this->session->flashdata('success')): ?>
        <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <form class="space-y-4" action="<?php echo base_url('auth/process_login'); ?>" method="POST">
      
      <div>
        <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Email Address</label>
       
        <input type="text" name="email" value="<?php echo set_value('email'); ?>" placeholder="username@domain.com" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm placeholder:text-slate-300">
        <?php if(form_error('email')): ?>
            <p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('email'); ?></p>
        <?php endif; ?>
      </div>

      <div>
        <label class="block text-xs font-bold uppercase tracking-wide text-slate-700">Account Password</label>
        <input type="password" name="password" placeholder="••••••••" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-1.5 focus:outline-indigo-600 text-sm placeholder:text-slate-300">
        <?php if(form_error('password')): ?>
            <p class="mt-1 text-xs font-semibold text-rose-600"><?php echo form_error('password'); ?></p>
        <?php endif; ?>
      </div>

      <button type="submit" class="w-full justify-center rounded-md bg-indigo-600 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 transition cursor-pointer">
          Login
      </button>

    </form>

    
    <p class="mt-6 text-center text-xs text-slate-500">
        Tidak ada akun?
        <a href="<?php echo base_url('auth/process_registration'); ?>" class="font-bold text-indigo-600 hover:text-indigo-500 transition">
            Silahkan Registrasi.
        </a>
    </p>

  </div>
</div>
