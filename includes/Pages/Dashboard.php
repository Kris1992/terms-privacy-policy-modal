<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Pages;

use \Kris1992\TermsPrivacyPolicyModal\Includes\Base\BaseController;
use \Kris1992\TermsPrivacyPolicyModal\Includes\Api\SettingsApi;
use \Kris1992\TermsPrivacyPolicyModal\Includes\Api\Callbacks\AdminCallbacks;
use \Kris1992\TermsPrivacyPolicyModal\Includes\Api\Callbacks\ManagerCallbacks;

/**
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
class Dashboard extends BaseController 
{

    private $settingsApi;

    private $adminCallbacks;

    private $managerCallbacks;

    private $pages = [];

	public function register() {
        $this->settingsApi = new SettingsApi();
        $this->adminCallbacks = new AdminCallbacks();
        $this->managerCallbacks = new ManagerCallbacks();
        $this->setPages();

        //Pola
        $this->setSettings();
        $this->setSections();
        $this->setFields();


        $this->settingsApi->addPages($this->pages)->withSubpage('Ustawienia ogólne')->register();
	}

    private function setPages()
    {
        $this->pages = [
            [
                'page_title' => 'Ustawienia regulaminów',
                'menu_title' => 'Ustawienia regulaminów',
                'capability' => 'manage_options',
                'menu_slug' => 'terms_privacy_policy_modal',
                'callback' => [$this->adminCallbacks, 'adminDashboard'], 
                'icon_url' => 'dashicons-store',
                'position' => 110,
            ]
        ];
    }

    //ustawianie pól w ustawieniach ogolnych
    public function setSettings()
    {
        $args[] = [
            'option_group' => 'terms_privacy_policy_modal_option_group', 
            'option_name' => 'terms_privacy_policy_modal',
            'callback' => [$this->managerCallbacks, 'sanitizeCheckbox']
        ];

        $this->settingsApi->setSettings($args);
    }

    public function setSections()
    {
        $args = [
            [
                'id' => 'terms_privacy_policy_modal_admin_index', 
                'title' => 'Ustawienia',  
                'callback' => [$this->managerCallbacks, 'adminSection'],
                'page' => 'terms_privacy_policy_modal'//menu slug
            ]
        ];

        $this->settingsApi->setSections($args);
    }

    public function setFields()
    {

        $args = [];

        foreach ($this->checkboxFields as $key => $value) {
            $args[] = [
                'id' => $key,// to samo co z option name
                'title' => $value,  
                'callback' => [$this->managerCallbacks, 'checkboxField'],
                'page' => 'terms_privacy_policy_modal',//menu slug
                'section' => 'terms_privacy_policy_modal_admin_index',//section id
                'args' => [
                    'option_name' => 'terms_privacy_policy_modal',
                    'label_for' => $key,
                    'class' => 'ui-toggle'
                ]
            ];
        };

        $this->settingsApi->setFields($args);
    }
}
