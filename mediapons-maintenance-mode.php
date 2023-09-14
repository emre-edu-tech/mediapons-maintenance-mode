<?php
/*
    Plugin Name: Media Pons Maintenance Mode
    Plugin URI: https://mediapons.de
    Description: This plugin displays a coming soon or maintenance page for anyone who is not logged in.
    Version: 1.0
    Author: Media Pons
    Author URI: https://mediapons.de
    Text Domain: mp-maintenance
    Domain Path: /languages
*/
if(!defined('ABSPATH')) exit;

class MpMaintenanceMode {
    function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'mp_maintenance_admin_style']);
        add_action('admin_menu', [$this, 'admin_page']);
        add_action('admin_init', [$this, 'admin_settings']);
        add_action('wp_loaded', [$this, 'mp_maintenance_mode']);
    }

    function mp_maintenance_admin_style($hook_suffix) {
        if($hook_suffix != 'settings_page_mediapons-maintenance') {
            return;
        }
        wp_enqueue_style('mediapons-maintenance-css', plugin_dir_url(__FILE__) . 'build/index.css');
    }

    // Text inputs for the maintenance page options
    // 1- Page title
    // 2- Company Name
    // 3- Logo
    // 4- Maintenance heading
    // 5- Description why the site is under construction
    // 6- Email Button url and text
    // 7- Phone Button Number and text

    // This function is responsible for adding the inputs for the settings on admin page
    function admin_settings() {
        // Add section on the page
        add_settings_section('mp_maintenance_section', __('Page Features', 'mp-maintenance'), null, 'mediapons-maintenance');

        // Page Title Field
        add_settings_field( 'mp_page_title', __('Page Title', 'mp-maintenance'), [$this, 'page_title_html'], 'mediapons-maintenance', 'mp_maintenance_section');
        register_setting('mpmaintenance', 'mp_page_title', [
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        // Company / Site name field
        add_settings_field( 'mp_company_name', __('Company / Site Title', 'mp-maintenance'), [$this, 'company_name_html'], 'mediapons-maintenance', 'mp_maintenance_section');
        register_setting('mpmaintenance', 'mp_company_name', [
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        // Logo
        add_settings_field( 'mp_company_logo', __('Company / Site Logo', 'mp-maintenance'), [$this, 'company_logo_html'], 'mediapons-maintenance', 'mp_maintenance_section');
        register_setting('mpmaintenance', 'mp_company_logo', [
            'sanitize_callback' => [$this, 'handle_logo_upload']
        ]);

        // Maintenance Heading
        add_settings_field( 'mp_maintenance_heading', __('Maintenance Heading', 'mp-maintenance'), [$this, 'maintenance_heading_html'], 'mediapons-maintenance', 'mp_maintenance_section');
        register_setting('mpmaintenance', 'mp_maintenance_heading', [
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        // Description why the site is under construction
        add_settings_field( 'mp_maintenance_description', __('Maintenance Description', 'mp-maintenance'), [$this, 'maintenance_description_html'], 'mediapons-maintenance', 'mp_maintenance_section');
        register_setting('mpmaintenance', 'mp_maintenance_description', [
            'sanitize_callback' => 'wp_kses_post',
        ]);

        // Email Button URL and text
        add_settings_field( 'mp_email_button_text', __('E-mail Button Text', 'mp-maintenance'), [$this, 'email_button_text_html'], 'mediapons-maintenance', 'mp_maintenance_section');
        register_setting('mpmaintenance', 'mp_email_button_text', [
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        add_settings_field( 'mp_email_button_address', __('E-mail Button Address', 'mp-maintenance'), [$this, 'email_button_address_html'], 'mediapons-maintenance', 'mp_maintenance_section');
        register_setting('mpmaintenance', 'mp_email_button_address', [
            'sanitize_callback' => 'sanitize_email',
        ]);

        //  Phone Button Number and text
        add_settings_field( 'mp_phone_button_text', __('Phone Button Text', 'mp-maintenance'), [$this, 'phone_button_text_html'], 'mediapons-maintenance', 'mp_maintenance_section');
        register_setting('mpmaintenance', 'mp_phone_button_text', [
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        add_settings_field( 'mp_phone_button_number', __('Phone Button Number', 'mp-maintenance'), [$this, 'phone_button_number_html'], 'mediapons-maintenance', 'mp_maintenance_section');
        register_setting('mpmaintenance', 'mp_phone_button_number', [
            'sanitize_callback' => 'sanitize_text_field',
        ]);
    }

    function phone_button_number_html() { ?>
        <input type="tel" name="mp_phone_button_number" value="<?php echo esc_attr(get_option('mp_phone_button_number')) ?>" placeholder="<?php _e('Enter Phone Number with Country Code (XX XXX XX XX)', 'mp-maintenance') ?>">
    <?php }

    function phone_button_text_html() { ?>
        <input type="text" name="mp_phone_button_text" value="<?php echo esc_attr(get_option('mp_phone_button_text')) ?>" placeholder="<?php _e('Enter Phone Button Text', 'mp-maintenance') ?>">
    <?php }

    function email_button_address_html() { ?>
        <input type="email" name="mp_email_button_address" value="<?php echo esc_attr(get_option('mp_email_button_address')) ?>" placeholder="<?php _e('Enter Email Address', 'mp-maintenance') ?>">
    <?php }

    function email_button_text_html() { ?>
        <input type="text" name="mp_email_button_text" value="<?php echo esc_attr(get_option('mp_email_button_text')) ?>" placeholder="<?php _e('Enter Email Button Text', 'mp-maintenance') ?>">
    <?php }

    function maintenance_description_html() {
        wp_editor(get_option('mp_maintenance_description'), 'mp_maintenance_description', [
            'media_buttons' => false,
            'textarea_rows' => 5,
            // removes unnecessary buttons from the editor
            'teeny' => true,
            'wpautop' => false
        ]);
    }

    function maintenance_heading_html() { ?>
        <input type="text" name="mp_maintenance_heading" value="<?php echo esc_attr(get_option('mp_maintenance_heading')) ?>" placeholder="<?php _e('Enter Maintenance Heading', 'mp-maintenance') ?>">
    <?php }

    function handle_logo_upload($input_val) {
        if(!empty($_FILES['mp_company_logo']['name'])) {
            $uploaded_file = wp_handle_upload($_FILES['mp_company_logo'], ['test_form' => false]);
            $uploaded_file_url = $uploaded_file['url'];
            return $uploaded_file_url;
        } else {
            if(get_option('mp_company_logo')) {
                return get_option('mp_company_logo');
            }
        }
        return $input_val;
    }

    function company_logo_html() { ?>
        <div class="flex items-center">
            <input type="file" name="mp_company_logo">
            <?php if(get_option('mp_company_logo', '') !== ''): ?>
                <span class="font-semibold mr-2"><?php _e('Current Logo:', ' mp-maintenance') ?></span>
                <img src="<?php echo get_option('mp_company_logo') ?>" alt="Logo">
            <?php else: ?>
                <span class="text-red-500 font-semibold"><?php _e('No logo file has been uploaded', 'mp-maintenance') ?></span>
            <?php endif; ?>
        </div>
    <?php }

    function company_name_html() { ?>
        <input type="text" name="mp_company_name" value="<?php echo esc_attr(get_option('mp_company_name')) ?>" placeholder="<?php _e('Enter Maintenance Mode Title', 'mp-maintenance') ?>">
    <?php }

    function page_title_html() { ?>
        <input type="text" name="mp_page_title" value="<?php echo esc_attr(get_option('mp_page_title')) ?>" placeholder="<?php _e('Enter Maintenance Page Browser Title', 'mp-maintenance') ?>">
    <?php }

    function settings_page_content() { ?>
        <div class="wrap">
            <h1><?php _e('Maintenance Page Settings', 'mp-maintenance') ?></h1>
            <form action="options.php" method="POST" enctype="multipart/form-data">
                <?php
                    // option_group: mpmaintenance
                    // page_slug: mediapons-maintenance
                    settings_fields('mpmaintenance');
                    do_settings_sections('mediapons-maintenance');
                    submit_button();
                ?>
            </form>
        </div>
    <?php }

    function admin_page() {
        add_options_page(__('Maintenance Page Settings', 'mp-maintenance'), __('Maintenance Page', 'mp-maintenance'), 'manage_options', 'mediapons-maintenance', [$this, 'settings_page_content']);
    }

    // Function for main functionality
    function mp_maintenance_mode() {
        global $pagenow;
        if($pagenow !== 'wp-login.php' && !current_user_can('manage_options') && !is_admin()) {
            // The code below may prevent the search engines to index our maintenance page 
            header($_SERVER["SERVER_PROTOCOL"] . ' 503 Service Temporarily Unavailable', true, 503);
		    header('Content-Type: text/html; charset=utf-8');
            if(file_exists(plugin_dir_path(__FILE__) . 'views/maintenance.php')) {
                require_once(plugin_dir_path(__FILE__) . 'views/maintenance.php');
            }
            die();
        }
    }
}

$mpMaintenanceMode = new MpMaintenanceMode();