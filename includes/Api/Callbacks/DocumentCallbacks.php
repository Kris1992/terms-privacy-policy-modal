<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Api\Callbacks;

/**
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 */

/**
 * Take care about documents taxonomy
 * @since      1.0.0
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 * @author     Kris1992
 */
class DocumentCallbacks 
{
    public function sectionDocumentsManager() {
        return;
    }

    public function sanitizeDocuments($input)
    {
        $output = get_option('terms_privacy_policy_modal_documents');

        if (isset($_POST['remove'])) {
            unset($output[$_POST['remove']]);

            return $output;
        }

        if (count($output) === 0) {
            $output[$input['taxonomy']] = $input;

            return $output;
        }

        foreach ($output as $key => $value) {
            if ($input['taxonomy'] === $key) {
                $output[$key] = $input;
            } else {
                $output[$input['taxonomy']] = $input;
            }
        }
        
        return $output;
    }

    public function textField($args)
    {
        $name = $args['label_for'];
        $optionName = $args['option_name'];
        $value = '';

        if (isset($_POST['edit_taxonomy'])) {
            $input = get_option($optionName);
            $value = $input[$_POST['edit_taxonomy']][$name];
        }

        echo '<input type="text" class="regular-text" id="' . $name . '" name="' . $optionName . '[' . $name . ']" value="' . $value . '" placeholder="' . $args['placeholder'] . '" required>';
    }

    public function checkboxField($args)
    {
        $name = $args['label_for'];
        $class = $args['class'];
        $optionName = $args['option_name'];
        $checked = false;

        if (isset($_POST['edit_taxonomy'])) {
            $checkbox = get_option($optionName);
            $checked = isset($checkbox[$_POST['edit_taxonomy']][$name]) ?: false;
        }

        echo '<div class="' . $class . '"><input type="checkbox" id="' . $name . '" name="' . $optionName . '[' . $name . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
    }
}
