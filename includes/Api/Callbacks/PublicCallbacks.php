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
 * Take care about show modal
 * @since      1.0.0
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 * @author     Kris1992
 */
class PublicCallbacks extends BaseController
{

    public function publicModal($tppmModalToAccept)// funkcja sprawdzona
    {
        $tppmDocumentsToAccept = [];
        //Get all taxonomies and merge all data about it to array
        $tppmModalId = (int)$tppmModalToAccept[0]['ID'];
        $taxonomies = get_post_taxonomies($tppmModalId);

        foreach ($taxonomies as $taxonomy) {
            $documentsData = get_the_terms($tppmModalId, $taxonomy);
            if (!empty($documentsData) && !is_wp_error($documentsData)) {
                foreach ($documentsData as $document) {
                    $tppmDocumentsToAccept[] = $document;
                }  
            }   
        }
        
        return require_once $this->pluginPath . '/templates/modal.php';
    }
}
