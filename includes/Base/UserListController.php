<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes\Base;

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
 *
 * @since      1.0.0
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 * @author     Kris1992
 */
class UserListController extends BaseController
{

    public function register() 
    {
        add_filter('manage_users_columns', [$this, 'modifyUserTable']);
        add_filter('manage_users_custom_column', [$this, 'modifyUserTableRow'], 10, 3);
    }

    public function modifyUserTableRow($val, $columnName, $userId ) {
        switch ($columnName) {
            case 'tppm_modal_accept':
                $data = unserialize(get_the_author_meta('tppm_last_modals', $userId));
                if (empty($data)) {
                    return 'Brak danych';
                }

                return $data['last']['date'] . ' --- v.' . $data['last']['version'];
        }
        return $val;
    }

    public function modifyUserTable($column) {
        $column['tppm_modal_accept'] = 'Ostatnia akceptacja warunków';
        return $column;
    }
}

