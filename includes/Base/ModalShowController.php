<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Base;

use \Kris1992\TermsPrivacyPolicyModal\Includes\Base\BaseController;
use \Kris1992\TermsPrivacyPolicyModal\Includes\Api\Callbacks\PublicCallbacks;

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
class ModalShowController extends BaseController
{

    private $currentUser = false;

    private $currentUserId = 0;

    private $activeTermsModal = [];

    private $modalsConfig = [];

    private $publicCallbacks;

    public function register() 
    {
        $this->modalsConfig = get_option('terms_privacy_policy_modal');
        $allowModals = isset($this->modalsConfig['enable_ttpm_modals']) && $this->modalsConfig['enable_ttpm_modals'];
        if (!$allowModals) {
            return;
        }

        $this->setupAjaxActions();

        $this->publicCallbacks = new PublicCallbacks();
        add_action('init', [$this, 'manageModal']);
    }

    public function ttpmSetUserTermsAjax()
    { 
        $this->currentUserId = (int)get_current_user_id();
        $this->activeTermsModal = wp_get_recent_posts(['post_type' => 'tppm_modals', 'posts_per_page' => 1]);

        if (empty($this->activeTermsModal) 
            || $this->currentUserId <= 0 
            || !$this->saveUserModalMetaAll()
            || ((int)update_user_meta($this->currentUserId, 'tppm_show_modal_state', 0)) <= 0
        ) {
            wp_send_json_error(['message' => 'Zapis danych zakończyl się niepowodzeniem'], 400);
        }
        
        wp_send_json_success(['message' => 'OK']);
    }

    /* That function decide to setup modal or not */
    public function toggleModalState()
    {
        $showAdminsModals = isset($this->modalsConfig['require_admins_checkbox']) 
        && $this->modalsConfig['require_admins_checkbox'];

        // If  user is admin and option show for admins is disabled just return;
        if (!$showAdminsModals && $this->userHasRole($this->currentUser)) {
            return;
        }

        //sprawdź aktualną wersję modala i porownaj z ostatnią zaakceptowaną
        $currentActiveModalId = (int)$this->activeTermsModal[0]['ID'];
        $lastAcceptedModalsMeta = unserialize(get_user_meta($this->currentUserId, 'tppm_last_modals', true));
        if ($currentActiveModalId <= (int)$lastAcceptedModalsMeta['last']['version']) {
            return;
        }
        
        $showModalState = get_user_meta($this->currentUserId, 'tppm_show_modal_state', true);
        if (empty($showModalState)) {
            update_user_meta($this->currentUserId, 'tppm_show_modal_state', 1);
        }
    }

    public function manageModal()
    {
        if ($GLOBALS['pagenow'] === 'wp-login.php' || is_admin()) {
            return;// If we are on login page just return
        }

        $this->currentUserId = (int)get_current_user_id();

        if (!is_user_logged_in() || $this->currentUserId <= 0) {
            return;
        }

        $this->currentUser = get_userdata($this->currentUserId);
        if (!$this->currentUser) {
            return;
        }
        
        $this->activeTermsModal = wp_get_recent_posts(['post_type' => 'tppm_modals', 'posts_per_page' => 1]);
        if (empty($this->activeTermsModal)) {
            return;
        }

        $this->toggleModalState();
        $this->showModal();
    }

    public function saveUserModalMetaAll()
    {
        // Last modals meta has only first and last modal data
        $lastModalsMeta = unserialize(get_user_meta($this->currentUserId, 'tppm_last_modals', true));
        $currentActiveModalId = (int)$this->activeTermsModal[0]['ID'];
        if ($currentActiveModalId <= 0) {
            return true;
        }

        $acceptedModalsMeta = unserialize(get_user_meta($this->currentUserId, 'tppm_accepted_modals', true));

        $result = true;
        $result &= $this->saveLastUserModalMeta($lastModalsMeta, $currentActiveModalId);
        $result &= $this->saveUserModalMeta($acceptedModalsMeta, $currentActiveModalId);
        return $result;
    }

    private function showModal()
    {
        $showModalState = get_user_meta($this->currentUserId, 'tppm_show_modal_state', true);
        if (empty($showModalState)) {
            return;
        }

        $this->publicCallbacks->publicModal($this->activeTermsModal);
    }

    /** Save only first and last accepted modal and return update state */
    private function saveLastUserModalMeta($lastModalsMeta, $currentActiveModalId) 
    {
        if (empty($lastModalsMeta)) {
            $termsData = [
                'first' => [
                    'date' => date('Y-m-d H:i:s'),
                    'version' => $currentActiveModalId,
                ],
                'last' => [
                    'date' => date('Y-m-d H:i:s'),
                    'version' => $currentActiveModalId,
                ]
            ];
            $serializedMeta = serialize($termsData);
            return ((int)add_user_meta($this->currentUserId, 'tppm_last_modals', $serializedMeta, true)) > 0;
        }

        //Do not need update
        if ((int)$lastModalsMeta['last']['version'] >= $currentActiveModalId) {
            return true;
        }

        $lastModalsMeta = [
            'last' => [
                'date' => date('Y-m-d H:i:s'),
                'version' => $currentActiveModalId,
            ]
        ];

        $serializedMeta = serialize($lastModalsMeta);
        return ((int)update_user_meta($this->currentUserId, 'tppm_last_modals', $serializedMeta)) > 0;
    }

    /** Save full array with accepted terms */
    private function saveUserModalMeta($acceptedModalsMeta, $currentActiveModalId) 
    {
        if (empty($acceptedModalsMeta)) {
            $newModalData = [
                [ 
                    'date' => date('Y-m-d H:i:s'),
                    'version' => $currentActiveModalId,
                ]
            ];

            $serializedMeta = serialize($newModalData);
            return ((int)add_user_meta($this->currentUserId, 'tppm_accepted_modals', $serializedMeta)) > 0;
        }

        $lastAcceptedModal = end($acceptedModalsMeta);
        //Do not need update
        if ((int)$lastAcceptedModal['version'] >= $currentActiveModalId) {
            return true;
        }

        array_push($acceptedModalsMeta, ['date' => date('Y-m-d H:i:s'), 'version' => $currentActiveModalId]);
        $serializedMeta = serialize($acceptedModalsMeta);
        return ((int)update_user_meta($this->currentUserId, 'tppm_accepted_modals', $serializedMeta)) > 0;

    }

    /* Function which check user has role */
    private function userHasRole($user, $role = 'administrator') 
    {
        if (!$user || !$user->roles) {
            return false;
        }

        if (is_array($role)) {
            return array_intersect($role, (array)$user->roles) ? true : false;
        }

        return in_array($role, (array)$user->roles);
    }

    private function setupAjaxActions()
    {
        add_action('wp_ajax_ttpmSetUserTerms', [$this, 'ttpmSetUserTermsAjax']);
    }
}
