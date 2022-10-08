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
 * Take cares about basics module settings
 * @since      1.0.0
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 * @author     Kris1992
 */
class ManagerCallbacks extends BaseController
{
    public function sanitizeCheckbox($input) 
    {
        $output = [];
        foreach ($this->checkboxFields as $key => $value) {
            $output[$key] = isset($input[$key]);
        }

        return $output;
    }

    public function adminSection() 
    {
        echo '<h4>Ustawienia modułu</h4>';
    }

    public function checkboxField($args) 
    {
        $name = $args['label_for'];
        $optionName = $args['option_name'];
        $class = $args['class'];
        $checkbox = get_option($optionName);
        $isChecked = isset($checkbox[$name]) && $checkbox[$name];
        echo '<input type="checkbox" name="'. $optionName . '[' . $name . ']" value="1" class="' . $class . '" ' . ($isChecked ? 'checked="checked"' : '') . ' >';
    }
}
