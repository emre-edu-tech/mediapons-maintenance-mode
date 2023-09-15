<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo get_option('mp_page_title', __('Down for Maintenance', 'mp-maintenance')) ?> - <?php bloginfo('name') ?>
    </title>
    <link rel="shortcut icon" href="<?php echo get_option('mp_company_logo') ? get_option('mp_company_logo') : plugin_dir_url(__DIR__) . 'img/logout-icon.svg' ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__DIR__) . 'build/index.css' ?>">
</head>

<body class="h-full">
    <!-- Content starts -->
    <main class="lg:max-w-6xl mx-auto grid min-h-full place-items-center bg-white px-6 py-16 lg:px-0">
        <div class="text-center">
            <h3 class="site-title mb-6 text-xl"><?php echo get_option('mp_company_name', get_bloginfo('name')) ?></h3>
            <a class="site-logo inline-block" href="<?php echo site_url('/') ?>">
                <?php if(get_option('mp_company_logo', '') !== ''): ?>
                    <img class="h-16 w-auto" src="<?php echo get_option('mp_company_logo') ?>" alt="<?php bloginfo('name') ?>">
                <?php else: ?>
                    <img class="h-16 w-auto" src="<?php echo plugin_dir_url(__DIR__) . 'img/logout-icon.svg' ?>" alt="<?php bloginfo('name') ?>">
                <?php endif; ?>
            </a>
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl"><?php echo get_option('mp_maintenance_heading', __('Under Construction', 'mp-maintenance')) ?></h1>
            <div class="mt-6 text-base leading-7 text-gray-600">
                <?php echo get_option('mp_maintenance_description', __('This is a sample description for maintenance mode.', 'mp-maintenance')) ?>
            </div>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="mailto:<?php echo get_option('mp_email_button_address', get_option('admin_email', '#')) ?>" class="rounded-md bg-gray-900 px-3.5 py-2.5 font-semibold text-white shadow-sm hover:bg-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                    <?php echo get_option('mp_email_button_text', __('E-mail us', 'mp-maintenance')) ?> <i class="ml-1 fa-solid fa-envelope"></i>
                </a>   
                <a href="tel:<?php echo str_replace(' ', '', get_option('mp_phone_button_number', '#')) ?>" class="border border-gray-900 px-3.5 py-2.5 rounded-md font-semibold text-gray-900"><?php echo get_option('mp_phone_button_text', __('Call us', 'mp-maintenance')) ?> <i class="ml-1 fa-solid fa-phone"></i></a>
            </div>
        </div>
    </main>
</body>

</html>