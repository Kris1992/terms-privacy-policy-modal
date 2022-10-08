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
class SettingsLinks extends BaseController
{

	public function register() {
        //dodanie linku w ustawieniach modulow
        
        add_filter('plugin_action_links_' . $this->pluginName, [$this, 'settingsLink']);
	}

    public function settingsLink($links) {
        $settingsLink =  '<a href="admin.php?page=terms_privacy_policy_modal">Ustawienia</a>';
        array_push($links, $settingsLink);
        return $links;
    }

}
