<?php
/**
 * Plugin Name: ProPopup - Premium Popup Builder
 * Plugin URI: https://www.abijita.com/pro-popup
 * Description: Professional popup plugin for ads, announcements, and custom content with auto-close timer
 * Version: 1.0.0
 * Author: Abijita Foundation
 * Author URI: https://www.abijita.com
 * Developer: Bijay Pokharel
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pro-popup
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PRO_POPUP_VERSION', '1.0.0');
define('PRO_POPUP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PRO_POPUP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PRO_POPUP_PLUGIN_FILE', __FILE__);

// Main Plugin Class
final class ProPopup {

    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', [$this, 'load_textdomain']);
    }

    public function init() {
        $this->includes();
        $this->init_classes();
        $this->hooks();
    }

    private function includes() {
        require_once PRO_POPUP_PLUGIN_DIR . 'includes/class-popup-install.php';
        require_once PRO_POPUP_PLUGIN_DIR . 'includes/class-popup-cpt.php';
        require_once PRO_POPUP_PLUGIN_DIR . 'includes/class-popup-metaboxes.php';
        require_once PRO_POPUP_PLUGIN_DIR . 'includes/class-popup-assets.php';
        require_once PRO_POPUP_PLUGIN_DIR . 'includes/class-popup-display.php';
        require_once PRO_POPUP_PLUGIN_DIR . 'includes/class-popup-settings.php';
        require_once PRO_POPUP_PLUGIN_DIR . 'includes/class-popup-ajax.php';
    }

    private function init_classes() {
        ProPopup_Install::init();
        ProPopup_CPT::init();
        ProPopup_Metaboxes::init();
        ProPopup_Assets::init();
        ProPopup_Display::init();
        ProPopup_Settings::init();
        ProPopup_Ajax::init();
    }

    private function hooks() {
        register_activation_hook(__FILE__, ['ProPopup_Install', 'activate']);
        register_deactivation_hook(__FILE__, ['ProPopup_Install', 'deactivate']);
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function load_textdomain() {
        load_plugin_textdomain('pro-popup', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function admin_menu() {
        add_submenu_page(
            'edit.php?post_type=pro_popup',
            __('Settings', 'pro-popup'),
            __('Settings', 'pro-popup'),
            'manage_options',
            'pro-popup-settings',
            [$this, 'render_settings_page']
        );
    }

    public function render_settings_page() {
        require_once PRO_POPUP_PLUGIN_DIR . 'admin/views/settings-page.php';
    }
}

// Initialize the plugin
function pro_popup() {
    return ProPopup::instance();
}

// Start the plugin
pro_popup();