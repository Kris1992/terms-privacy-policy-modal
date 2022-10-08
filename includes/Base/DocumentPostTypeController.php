<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Base;

use \Kris1992\TermsPrivacyPolicyModal\Includes\Base\BaseController;
use \Kris1992\TermsPrivacyPolicyModal\Includes\Api\SettingsApi;
use \Kris1992\TermsPrivacyPolicyModal\Includes\Api\Callbacks\AdminCallbacks;
use \Kris1992\TermsPrivacyPolicyModal\Includes\Api\Callbacks\DocumentCallbacks;

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
class DocumentPostTypeController extends BaseController
{
    private $settingsApi;

    private $adminCallbacks;

    private $documentCallbacks;

    private $subpages = [];

    private $documents = [];

    public function register() {
        $this->settingsApi = new SettingsApi();
        $this->adminCallbacks = new AdminCallbacks();
        $this->documentCallbacks = new DocumentCallbacks();

        $this->setSubpages();

        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settingsApi->addSubpages($this->subpages)->register();

        $this->storeCustomDocuments();

        if (!empty($this->documents) ) {
            add_action('init', [$this, 'registerCustomDocument']);
            add_filter('tag_row_actions', [$this, 'removeRowActionsTerm'], 10, 2);
            add_action('pre_delete_term', [$this, 'restrictTaxonomyDeletion'], 10, 2);
            add_action('edit_terms', [$this, 'restrictTaxonomyUpdate'], 10, 2);
        }
    }

    public function removeRowActionsTerm($actions, $term)
    {
        if (strpos($term->taxonomy, 'tppmd_') !== false) {
            unset($actions['inline hide-if-no-js']);
            unset($actions['delete']);
            unset($actions['edit']);
        }

        return $actions;
    }

    public function restrictTaxonomyDeletion($term, $taxonomy) 
    {
        if (strpos($taxonomy, 'tppmd_') !== false) {
            wp_die('Dokumenty tego typu są chronione');
        }
    }


    public function restrictTaxonomyUpdate($termId, $taxonomy) 
    {
        if (strpos($taxonomy, 'tppmd_') !== false) {
            wp_die('Dokumenty tego typu są chronione');
        }
    }

    private function setSubpages()
    {
        $this->subpages = [
            [   
                'parent_slug' => 'terms_privacy_policy_modal',
                'page_title' => 'Zarządzaj dokumentami',
                'menu_title' => 'Zarządzaj dokumentami',
                'capability' =>  'manage_options',
                'menu_slug' =>  'terms_privacy_policy_modal_documents',
                'callback' => [$this->adminCallbacks, 'adminDocuments'], 
            ],
        ];
    }

    private function setSettings()
    {
        $args[] = [
            'option_group' => 'terms_privacy_policy_modal_documents_option_group', 
            'option_name' => 'terms_privacy_policy_modal_documents',
            'callback' => [$this->documentCallbacks, 'sanitizeDocuments']
        ];

        $this->settingsApi->setSettings($args);
    }

    private function setSections()
    {
        $args = [
            [
                'id' => 'terms_privacy_policy_modal_documents_index',
                'title' => 'Menadżer dokumentów',
                'callback' => [$this->documentCallbacks, 'sectionDocumentsManager'],
                'page' => 'terms_privacy_policy_modal_documents'
            ]
        ];

        $this->settingsApi->setSections($args);
    }

    private function setFields()
    {
        $args = [
            [
                'id' => 'taxonomy',
                'title' => 'Typ dokumentu (liczba mnoga)',
                'callback' => [$this->documentCallbacks, 'textField'],
                'page' => 'terms_privacy_policy_modal_documents',
                'section' => 'terms_privacy_policy_modal_documents_index',
                'args' => [
                    'option_name' => 'terms_privacy_policy_modal_documents',
                    'label_for' => 'taxonomy',
                    'placeholder' => 'np. Regulaminy, Rolityki prywatności',
                    'array' => 'taxonomy'
                ]
            ],
            [
                'id' => 'singular_name',
                'title' => 'Nazwa pojedyńczego dokumentu',
                'callback' => [$this->documentCallbacks, 'textField'],
                'page' => 'terms_privacy_policy_modal_documents',
                'section' => 'terms_privacy_policy_modal_documents_index',
                'args' => [
                    'option_name' => 'terms_privacy_policy_modal_documents',
                    'label_for' => 'singular_name',
                    'placeholder' => 'np. Regulamin, polityka prywatności',
                    'array' => 'taxonomy'
                ]
            ],
            [
                'id' => 'hierarchical',
                'title' => 'Wyświetlaj w widgecie',
                'callback' => [$this->documentCallbacks, 'checkboxField'],
                'page' => 'terms_privacy_policy_modal_documents',
                'section' => 'terms_privacy_policy_modal_documents_index',
                'args' => [
                    'option_name' => 'terms_privacy_policy_modal_documents',
                    'label_for' => 'hierarchical',
                    'class' => 'ui-toggle',
                    'array' => 'taxonomy'
                ]
            ]
        ];

        $this->settingsApi->setFields($args);
    }
    
    private function storeCustomDocuments()
    {
        $options = get_option('terms_privacy_policy_modal_documents') ? get_option('terms_privacy_policy_modal_documents') : [];

        foreach ($options as $option) {
            $labels = [
                'name'              => $option['singular_name'],
                'singular_name'     => $option['singular_name'],
                'search_items'      => 'Search ' . $option['singular_name'],
                'all_items'         => 'All ' . $option['singular_name'],
                'parent_item'       => 'Parent ' . $option['singular_name'],
                'parent_item_colon' => 'Parent ' . $option['singular_name'] . ':',
                'edit_item'         => 'Edit ' . $option['singular_name'],
                'update_item'       => 'Update ' . $option['singular_name'],
                'add_new_item'      => 'Add New ' . $option['singular_name'],
                'new_item_name'     => 'New ' . $option['singular_name'] . ' Name',
                'menu_name'         => $option['singular_name'],
            ];

            $this->documents[] = [
                'hierarchical'      => isset($option['hierarchical']),
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => ['slug' => $option['taxonomy']],
            ];
        }
    }

    public function registerCustomDocument()
    {
        foreach ($this->documents as $document) {
            register_taxonomy('tppmd_' . $document['rewrite']['slug'], ['tppm_modals'], $document);
        }
    }
}

