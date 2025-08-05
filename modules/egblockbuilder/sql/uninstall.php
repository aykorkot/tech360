<?php
/**
 * 2019 (c) Egio digital
 *
 * MODULE EgStaticBlock
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egblockbuilder`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egblockbuilder_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egblockbuilder_shop`';

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egblockbuilder_items`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egblockbuilder_items_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egblockbuilder_items_shop`';

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'multiple_images`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
