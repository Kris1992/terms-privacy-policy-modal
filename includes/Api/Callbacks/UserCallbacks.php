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
                $filenamesArray = [];
                $postId = (int)$tppmDocumentData['version'];
                $relatedAttachments = get_attached_media('application/pdf', $postId);
                $dateTime = new \DateTime($tppmDocumentData['date'], new \DateTimeZone('Europe/Warsaw'));
                foreach ($relatedAttachments as $relatedAttachment) {
                    $filename = basename(parse_url($relatedAttachment->guid, PHP_URL_PATH));
                    array_push($filenamesArray, $filename);
                    $tppmDocumentData['attachmentName'] = $filename;
                    $tppmDocumentData['attachmentLink'] = $relatedAttachment->guid;
                    $tppmDocumentData['printDate'] = $dateTime->format('d/m/Y');
                    $tppmDocumentData['printTime'] = $dateTime->format('H:i:s');
                    $tppmFinalDocumentsData[] = $tppmDocumentData;
                }
                $externalAttachmentsLinks = $this->getExternalAttachmentsLinks($postId, $tppmFinalDocumentsData, $filenamesArray);
                foreach ($externalAttachmentsLinks as $externalAttachmentLink) {
                    $externalAttachment = get_post(attachment_url_to_postid($externalAttachmentLink));
                    if (is_object($externalAttachment)) {
                        $filename = basename(parse_url($externalAttachment->guid, PHP_URL_PATH));
                        $tppmDocumentData['attachmentName'] = $filename;
                        $tppmDocumentData['attachmentLink'] = $externalAttachment->guid;
                        $tppmDocumentData['printDate'] = $dateTime->format('d/m/Y');
                        $tppmDocumentData['printTime'] = $dateTime->format('H:i:s');
                        $tppmFinalDocumentsData[] = $tppmDocumentData;
                    }
                }
            } 
        }

        return require_once $this->pluginPath . '/templates/userProfile.php';
    }

    /** Get attachments copied from other post */
    public function getExternalAttachmentsLinks(int $postId, array $tppmFinalDocumentsData, array $filenamesArray): array
    {
        $finalAttachmentsLinks = [];
        $content = get_post_field('post_content', $postId);
        $attachmentsLinks = wp_extract_urls($content);
        foreach ($attachmentsLinks as $attachmentLink) {
            $filename = basename(parse_url($attachmentLink, PHP_URL_PATH));
            if (preg_match('/.pdf/', $filename) && !in_array($filename, $filenamesArray)) {
                array_push($finalAttachmentsLinks, $attachmentLink);
            }
        }
        return $finalAttachmentsLinks;
    }
}
