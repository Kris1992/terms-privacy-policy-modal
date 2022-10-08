<?php

namespace Kris1992\TermsPrivacyPolicyModal\Includes;

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
final class Init {

    public static function get_services() {
        return [
            Pages\Dashboard::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Base\ModalPostTypeController::class,
            Base\DocumentPostTypeController::class,
            Base\ModalShowController::class,
            Base\UserListController::class,
            Base\UserController::class,
        ];
    }

    public static function register_services() {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    private static function instantiate($class) {
        return new $class();
    }

}
