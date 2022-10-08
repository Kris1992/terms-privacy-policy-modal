<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Api\Callbacks;

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
 * Render admin module area 
 * @since      1.0.0
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 * @author     Kris1992
 */
class AdminCallbacks extends BaseController
{

    public function adminDashboard() 
    {
        return require_once $this->pluginPath . '/templates/admin.php';
    }

    public function adminDocuments()
    {
        $option = get_option('terms_privacy_policy_modal');
        $allowDocumentsActions = isset($option['document_cpt_manager']) && $option['document_cpt_manager'];

        return require_once $this->pluginPath . '/templates/documents.php';
    }
}
