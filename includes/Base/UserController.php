<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Base;

use \Kris1992\TermsPrivacyPolicyModal\Includes\Base\BaseController;
use \Kris1992\TermsPrivacyPolicyModal\Includes\Api\Callbacks\UserCallbacks;


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
class UserController extends BaseController
{
    /**
     * Show extra profile fields
     * @var UserCallbacks
     */
    private $userCallbacks;

    public function register() 
    {
        $this->userCallbacks = new UserCallbacks();
        add_action('show_user_profile', [$this, 'showExtraProfileFields']);
        add_action('edit_user_profile', [$this, 'showExtraProfileFields']);
    }

    public function showExtraProfileFields($user) {
        $this->userCallbacks->showUserProfile($user);
    }
}
