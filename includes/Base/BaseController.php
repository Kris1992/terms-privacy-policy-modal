<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Base;

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
class BaseController 
{

    public $pluginPath;

    public $pluginUrl;//assets

    public $pluginName;

    public $checkboxFields;

    public function __construct()
    {
        $this->pluginPath = plugin_dir_path(dirname(__FILE__, 2));
        $this->pluginUrl = plugin_dir_url(dirname(__FILE__, 2));
        $this->pluginName = plugin_basename(dirname(__FILE__, 3)) . '/terms-privacy-policy-modal.php';
        $this->checkboxFields = [
            'enable_ttpm_modals' => 'Włącz funkcjonalność modali',
            'modal_cpt_manager' => 'Umożliwiaj tworzenie modali', 
            'document_cpt_manager' => 'Umożliwiaj tworzenie/edycję typów dokumentów (uwaga opcja niebezpieczna)', 
            'require_admins_checkbox' => 'Wyświetlaj modale adminom'
        ];
    }

}
