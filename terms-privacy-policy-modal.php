<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Terms_Privacy_Policy_Modal
 *
 * @wordpress-plugin
 * Plugin Name:       Terms Privacy Policy Modal
 * Description:       It allows you to display a modal with consents to the privacy policy and regulations
 * Version:           1.0.0
 * Author:            Kris1992
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       terms-privacy-policy-modal
 * Domain Path:       /languages
 * 
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

if (!file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    die('Missing autoload file');
}

require dirname(__FILE__) . '/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/Kris1992/terms-privacy-policy-modal',
    __FILE__,
    'terms-privacy-policy-modal'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');

//Optional: If you're using a private repository, specify the access token like this:
// $myUpdateChecker->setAuthentication('your-token-here');

require_once dirname(__FILE__) . '/vendor/autoload.php';
use Kris1992\TermsPrivacyPolicyModal\Includes\Base\Activator;
use Kris1992\TermsPrivacyPolicyModal\Includes\Base\Deactivator;

function activate_terms_privacy_policy_modal() {
    Activator::activate();
}

function deactivate_terms_privacy_policy_modal() {
    Deactivator::deactivate();
}

// Activation
register_activation_hook(__FILE__, 'activate_terms_privacy_policy_modal');

//Deactivation
register_deactivation_hook(__FILE__, 'deactivate_terms_privacy_policy_modal');

if (class_exists('Kris1992\\TermsPrivacyPolicyModal\\Includes\\Init')) {
    Kris1992\TermsPrivacyPolicyModal\Includes\Init::register_services();
}
