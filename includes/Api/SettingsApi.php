<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Api;

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
class SettingsApi
{
    private $adminPages = [];

    private $adminSubpages = [];

    private $settings = [];

    private $sections = [];

    private $fields = [];

    public function register() {
        if (!empty($this->adminPages) || !empty($this->adminSubpages)) {
            add_action('admin_menu', [$this, 'addAdminMenu']);
        }

        if (!empty($this->settings)) {
            add_action('admin_init', [$this, 'registerCustomFields']);
        }    
    }

    public function addPages(array $pages)
    {
        $this->adminPages = $pages;

        return $this;
    }

    public function withSubpage(string $title = '') 
    {
        if (empty($this->adminPages) || $title === '') {
            return $this;
        }

        $adminPage = $this->adminPages[0];
        $subpage = [
            [   
                'parent_slug' => $adminPage['menu_slug'],
                'page_title' => $adminPage['page_title'],
                'menu_title' => $title,
                'capability' =>  $adminPage['capability'],
                'menu_slug' => $adminPage['menu_slug'],
                'callback' => function () { echo '';}, 
            ]
        ];

        $this->adminSubpages = $subpage;
        return $this;
    }

    public function addSubpages(array $pages)
    {
        $this->adminSubpages = array_merge($this->adminSubpages, $pages);

        return $this;
    }


    public function addAdminMenu() 
    {
        foreach ($this->adminPages as $page) {
             // terms_privacy_policy_modal musi być unique i zawsze małymi i podłogą
            add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position']);
        }

        foreach ($this->adminSubpages as $subpage) {
             // terms_privacy_policy_modal musi być unique i zawsze małymi i podłogą
            add_submenu_page($subpage['parent_slug'], $subpage['page_title'], $subpage['menu_title'], $subpage['capability'], $subpage['menu_slug'], $subpage['callback']);
        }
    }

    public function setSettings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    public function setSections(array $sections)
    {
        $this->sections = $sections;

        return $this;
    }

    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function registerCustomFields()
    {
        foreach ($this->settings as $setting) {
            register_setting($setting['option_group'], $setting['option_name'], (isset($setting['callback']) ? $setting['callback'] : ''));
        }
        foreach ($this->sections as $section) {
            add_settings_section($section['id'], $section['title'], (isset($section['callback']) ? $section['callback'] : ''), $section['page']);
        }
        foreach ($this->fields as $field) {
            add_settings_field($field['id'], $field['title'], (isset($field['callback']) ? $field['callback'] : ''), $field['page'], $field['section'], (isset($field['args']) ? $field['args'] : ''));
        }
    }
}
