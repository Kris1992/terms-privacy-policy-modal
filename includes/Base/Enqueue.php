<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Base;

use \Kris1992\TermsPrivacyPolicyModal\Includes\Base\BaseController;

/**
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 */


/**
 *
 * @since      1.0.0
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 * @author     Kris1992
 */
class Enqueue extends BaseController 
{

    public function register() {
        //jeśli chcemy po froncie to  'wp_enqueue_scripts'
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueuePublic']);
    }

    public function enqueueAdmin() {
        $pluginUrl = plugin_dir_url(dirname(__FILE__, 2));
        wp_enqueue_style('tppmPluginStyleAdmin', $pluginUrl . 'assets/css/tppmStyle.css');
        wp_enqueue_script('tppmPluginScriptAdmin', $pluginUrl . 'assets/js/tppmScript.js');
    }

    public function enqueuePublic() {
        $pluginUrl = plugin_dir_url(dirname(__FILE__, 2));

        // Prevent if something in Controller goes wrong
        if (function_exists('is_cart') && function_exists('is_checkout') && function_exists('is_page')) {
            if (is_cart() || is_page('cart') || is_checkout()) {
                wp_enqueue_script('tppmPluginScriptPublic', $pluginUrl . 'assets/js/tppmRemoveModal.js', ['jquery']);
                return;
            }
        }
    
        wp_enqueue_style('tppmPluginStylePublic', $pluginUrl . 'assets/css/tppmModal.css');
        wp_enqueue_script('tppmPluginScriptPublic', $pluginUrl . 'assets/js/tppmModal.js', ['jquery']);
        wp_localize_script('tppmPluginScriptPublic', 'urlHandler', ['ajaxUrl' => admin_url('admin-ajax.php')]);
    }
}