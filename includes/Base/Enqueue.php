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
        wp_enqueue_style('tppmPluginStylePublic', $pluginUrl . 'assets/css/tppmModal.css');
        wp_enqueue_script('tppmPluginScriptPublic', $pluginUrl . 'assets/js/tppmModal.js', ['jquery']);
        wp_localize_script('tppmPluginScriptPublic', 'urlHandler', ['ajaxUrl' => admin_url('admin-ajax.php')]);
    }
}