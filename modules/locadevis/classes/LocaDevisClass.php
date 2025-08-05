<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

class LocaDevisClass extends ObjectModel
{
    public $id_locadevis;
    public $id_product;
    public $email;
    public $firstname;
    public $lastname;
    public $phone;
    public $product_choice;
    public $departure_agency;
    public $return_agency;
    public $departure_date;
    public $return_date;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'locadevis',
        'primary' => 'id_locadevis',
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'email' => array('type' => self::TYPE_STRING, 'validate' => 'isEmail', 'required' => true),
            // 'customer_id' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'firstname' => array('type' => self::TYPE_STRING, 'validate' => 'isName'),
            'lastname' => array('type' => self::TYPE_STRING, 'validate' => 'isName'),
            'phone' => array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber'),
            'product_choice' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'departure_agency' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'return_agency' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),

            'departure_date' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'return_date' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),

        ),
    );

}
