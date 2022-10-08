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
 * Add extra fields to user profile
 * @since      1.0.0
 * @package    Terms_Privacy_Policy_Modal
 * @subpackage Terms_Privacy_Policy_Modal/includes
 * @author     Kris1992
 */
class UserCallbacks extends BaseController
{
    public function showUserProfile($user)
    {
        $tppmFinalDocumentsData = [];
        if (isset($user)) {
            $tppmDocumentsData = unserialize(get_user_meta((int)$user->ID, 'tppm_accepted_modals', true));

            foreach ($tppmDocumentsData as $tppmDocumentData) {
                $relatedAttachments = get_attached_media('application/pdf', (int)$tppmDocumentData['version']);
                foreach ($relatedAttachments as $relatedAttachment) {
                    $filename = basename(parse_url($relatedAttachment->guid, PHP_URL_PATH));
                    $tppmDocumentData['attachmentName'] = $filename;
                    $tppmDocumentData['attachmentLink'] = $relatedAttachment->guid;
                    $dateTime = new \DateTime($tppmDocumentData['date'], new \DateTimeZone('Europe/Warsaw'));
                    $tppmDocumentData['printDate'] = $dateTime->format('d/m/Y');
                    $tppmDocumentData['printTime'] = $dateTime->format('H:i:s');
                    $tppmFinalDocumentsData[] = $tppmDocumentData;
                }
            } 
        }
        return require_once $this->pluginPath . '/templates/userProfile.php';
    }
}
