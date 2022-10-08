<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Base;

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 * @author     Kris1992
 */
class Activator 
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        flush_rewrite_rules();

        // Tworzymy defaultową opcję
        $default = [];

        if (!get_option('terms_privacy_policy_modal_documents')) {
            update_option('terms_privacy_policy_modal_documents', $default);
        }
	}

}
