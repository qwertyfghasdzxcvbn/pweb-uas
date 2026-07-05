<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Model Mockup'; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/output.css?v=' . time()); ?>">
</head>

<body class="bg-slate-800 text-slate-50 flex flex-col min-h-screen font-sans antialiased">

   
    <?php $this->load->view('template/header'); ?>
   
    <main class="grow container mx-auto px-6 py-8">
        <?php $this->load->view($subview); ?>
    </main>

    <?php $this->load->view('template/footer'); ?>

</body>
</html>
