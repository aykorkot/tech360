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


    $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'egproduitpopulaire` (
        `id_egproduitpopulaire` INT(11) NOT NULL AUTO_INCREMENT,
        `id_category` INT(11) NOT NULL,
        `hook_names` TEXT NOT NULL,
        `lien` TEXT NOT NULL, 
        `limit_nb` int(10) unsigned NOT NULL DEFAULT 8,
        `position` int(10) unsigned NOT NULL DEFAULT 0,
        `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
        PRIMARY KEY (`id_egproduitpopulaire`)
    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

    $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'egproduitpopulaire_lang` (
        `id_egproduitpopulaire` INT(11) NOT NULL,
        `id_lang` INT(11) NOT NULL,
        `meta_title` TEXT NOT NULL, 
        `description` TEXT NOT NULL,
        `label` TEXT NOT NULL,
        `id_shop` int(10) unsigned NOT NULL DEFAULT 1,
        PRIMARY KEY (`id_egproduitpopulaire`,`id_shop`, `id_lang`)
    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

    $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'egproduitpopulaire_shop` (
        `id_egproduitpopulaire` INT(11) NOT NULL,
        `id_shop` INT(11) NOT NULL,
        PRIMARY KEY (`id_egproduitpopulaire`, `id_shop`)
    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
