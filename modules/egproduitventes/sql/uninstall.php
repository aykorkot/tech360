<?php
/**
 * 2019 (c) Egio digital
 *
 * MODULE EgProduitVentes
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egproduitventes`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egproduitventes_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egproduitventes_shop`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
