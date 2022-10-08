<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Base;

use \Kris1992\TermsPrivacyPolicyModal\Includes\Base\BaseController;
use \Kris1992\TermsPrivacyPolicyModal\Includes\Api\SettingsApi;
use \Kris1992\TermsPrivacyPolicyModal\Includes\Api\Callbacks\AdminCallbacks;

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
class ModalPostTypeController extends BaseController
{
    private $settingsApi;

    private $modalPostTypes = [];

    private $adminCallbacks;

    public function register() 
    {
        $option = get_option('terms_privacy_policy_modal');
        $isActivated = isset($option['modal_cpt_manager']) && $option['modal_cpt_manager'];

        if (!$isActivated) {
            return;
        }

        $this->settingsApi = new SettingsApi();
        $this->adminCallbacks = new AdminCallbacks();

        $this->storeModalPostTypes();

        if (empty($this->modalPostTypes)) {
            return;
        }

        add_action('init', [$this, 'registerModalCPT']);
        add_filter('manage_tppm_modals_posts_columns', [$this, 'modifyTPPMModalsTable']);
        add_filter('manage_tppm_modals_posts_custom_column', [$this, 'modifyTPPMModalsTableRow'], 10, 2);
        //remove and disable edit/remove/clone
        add_filter('post_row_actions', [$this, 'removeRowActionsPost'], 10, 2);
        add_action('pre_post_update', [$this, 'restrictPostUpdate'], 10, 2);//Before update action
        add_action('wp_trash_post', [$this, 'restrictPostDeletion']);//Disallow delete action
    }

    public function modifyTPPMModalsTable($column)
    {
        $column['tppm_modal_id'] = 'Wersja';
        return $column;
    }

    public function modifyTPPMModalsTableRow($columnName, $modalId) 
    {
        switch ($columnName) {
            case 'tppm_modal_id':
                echo $modalId;
        }
    }

    public function modifyUserTable($column) {
        $column['tppm_modal_accept'] = 'Ostatnia akceptacja warunków';
        return $column;
    }

    public function removeRowActionsPost($actions, $post) {
        if ($post->post_type === 'tppm_modals') {
            unset($actions['inline hide-if-no-js']);
            unset($actions['clone']);
            unset($actions['trash']);
            unset($actions['edit']);
        }

        return $actions;
    }

    public function restrictPostUpdate($postId, $data) {
        if (strpos(wp_get_raw_referer(), 'post-new') > 0) {
            return; //new post
        }
        
        if (get_post_type($postId) === 'tppm_modals') {
            wp_die('Modale związane z zgodami na regulaminy lub polityki prywatności nie podlegają edycji.');
        }
    }

    public function restrictPostDeletion($postId) {
        if (get_post_type($postId) === 'tppm_modals') {
            wp_die('Modale związane z zgodami na regulaminy lub polityki prywatności nie podlegają usunięciu.');
        }
    }

    public function registerModalCPT() 
    {
        foreach ($this->modalPostTypes as $modal) {
            register_post_type($modal['post_type'],
                [
                    'labels' => array(
                        'name'                  => $modal['name'],
                        'singular_name'         => $modal['singular_name'],
                        'menu_name'             => $modal['menu_name'],
                        'name_admin_bar'        => $modal['name_admin_bar'],
                        'archives'              => $modal['archives'],
                        'attributes'            => $modal['attributes'],
                        'parent_item_colon'     => $modal['parent_item_colon'],
                        'all_items'             => $modal['all_items'],
                        'add_new_item'          => $modal['add_new_item'],
                        'add_new'               => $modal['add_new'],
                        'new_item'              => $modal['new_item'],
                        'edit_item'             => $modal['edit_item'],
                        'update_item'           => $modal['update_item'],
                        'view_item'             => $modal['view_item'],
                        'view_items'            => $modal['view_items'],
                        'search_items'          => $modal['search_items'],
                        'not_found'             => $modal['not_found'],
                        'not_found_in_trash'    => $modal['not_found_in_trash'],
                        'featured_image'        => $modal['featured_image'],
                        'set_featured_image'    => $modal['set_featured_image'],
                        'remove_featured_image' => $modal['remove_featured_image'],
                        'use_featured_image'    => $modal['use_featured_image'],
                        'insert_into_item'      => $modal['insert_into_item'],
                        'uploaded_to_this_item' => $modal['uploaded_to_this_item'],
                        'items_list'            => $modal['items_list'],
                        'items_list_navigation' => $modal['items_list_navigation'],
                        'filter_items_list'     => $modal['filter_items_list']
                    ),
                    'label'                     => $modal['label'],
                    'description'               => $modal['description'],
                    'supports'                  => $modal['supports'],
                    'taxonomies'                => $modal['taxonomies'],
                    'hierarchical'              => $modal['hierarchical'],
                    'public'                    => $modal['public'],
                    'show_ui'                   => $modal['show_ui'],
                    'show_in_menu'              => $modal['show_in_menu'],
                    'menu_position'             => $modal['menu_position'],
                    'show_in_admin_bar'         => $modal['show_in_admin_bar'],
                    'show_in_nav_menus'         => $modal['show_in_nav_menus'],
                    'can_export'                => $modal['can_export'],
                    'has_archive'               => $modal['has_archive'],
                    'exclude_from_search'       => $modal['exclude_from_search'],
                    'publicly_queryable'        => $modal['publicly_queryable'],
                    'capability_type'           => $modal['capability_type'],
                    'capabilities'              => [ 
                        'delete_post'           => 'do_not_allow'
                    ],
                    'map_meta_cap'              => true,//disalow edit/delete
                ]
            );
        }
    }

    private function storeModalPostTypes()
    {
        $pluralName = 'Modale';
        $singularName = 'Modal';
        $this->modalPostTypes[] = [
            'post_type'             => 'tppm_modals',
                'name'                  => $pluralName,
                'singular_name'         => $singularName,
                'menu_name'             => $pluralName,
                'name_admin_bar'        => $singularName,
                'archives'              => $singularName . ' Archives',
                'attributes'            => $singularName . ' Attributes',
                'parent_item_colon'     => 'Parent ' . $singularName,
                'all_items'             => 'Wszystkie ' . $pluralName,
                'add_new_item'          => 'Dodaj ' . $singularName,
                'add_new'               => 'Dodaj',
                'new_item'              => 'Nowy ' . $singularName,
                'edit_item'             => 'Edytuj ' . $singularName,
                'update_item'           => 'Edytuj ' . $singularName,
                'view_item'             => 'Zobacz ' . $singularName,
                'view_items'            => 'Zobacz ' . $pluralName,
                'search_items'          => 'Szukaj ' . $pluralName,
                'not_found'             => 'Nie znaleziono ' . $singularName . 'i',
                'not_found_in_trash'    => 'W koszu nie znaleziono ' . $singularName . 'i',
                'featured_image'        => 'Featured Image',
                'set_featured_image'    => 'Set Featured Image',
                'remove_featured_image' => 'Remove Featured Image',
                'use_featured_image'    => 'Use Featured Image',
                'insert_into_item'      => 'Insert into ' . $singularName,
                'uploaded_to_this_item' => 'Upload to this ' . $singularName,
                'items_list'            => $pluralName . ' List',
                'items_list_navigation' => $pluralName . ' List Navigation',
                'filter_items_list'     => 'Filter' . $pluralName . ' List',
                'label'                 => $singularName,
                'description'           => $pluralName . 'Custom Post Type',
                'supports'              => ['title', 'editor', 'thumbnail'],
                'taxonomies'            => [],
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_position'         => 5,
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => true,
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'post'
        ];
    }
}
