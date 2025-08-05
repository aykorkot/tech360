<?php
/**
 * 2019 (c) Egio digital
 *
 * MODULE EgStaticBlock
 *
 * @version    1.0.0
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'egblockbuilder` (
    `id_egblockbuilder` INT(11) NOT NULL AUTO_INCREMENT,
    `id_category` INT(11) NOT NULL,
    `position` int(10) unsigned NOT NULL DEFAULT 0,  
    `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
    PRIMARY KEY (`id_egblockbuilder`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'egblockbuilder_lang` (
    `id_egblockbuilder` INT(11) NOT NULL,
    `id_lang` INT(11) NOT NULL,
    `meta_title` TEXT NOT NULL,
    `link_rewrite` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    `id_shop` int(10) unsigned NOT NULL DEFAULT 1,
    PRIMARY KEY (`id_egblockbuilder`,`id_shop`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'egblockbuilder_shop` (
    `id_egblockbuilder` INT(11) NOT NULL,
    `id_shop` INT(11) NOT NULL,
    PRIMARY KEY (`id_egblockbuilder`, `id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'egblockbuilder_items` (
    `id_egblockbuilder_items` INT(11) NOT NULL AUTO_INCREMENT,
    `id_egblockbuilder` INT(11) UNSIGNED NOT NULL,
    `type` INT(11) UNSIGNED NOT NULL, 
    `nb_produit` INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `banner` TEXT NOT NULL,
    `banner_mobile` TEXT NOT NULL,
    `chosen_products` TEXT NOT NULL,
    `background_color` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `link_button` VARCHAR(255) NOT NULL,
    `text_button` VARCHAR(255) NOT NULL,
    `link_button_3` VARCHAR(255) NOT NULL,
    `text_button_3` VARCHAR(255) NOT NULL,
    `type_video_banniere` VARCHAR(255) NOT NULL,
    `video_banniere` TEXT NOT NULL,
    `position` INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`id_egblockbuilder_items`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'egblockbuilder_items_lang` (
    `id_egblockbuilder_items` INT(11) NOT NULL,
    `id_lang` INT(11) NOT NULL,
    `id_shop` INT(11) NOT NULL,
    `title_1` TEXT NOT NULL,
    `title_2` TEXT NOT NULL,
    `title_3` TEXT NOT NULL,
    `title_4` TEXT NOT NULL,
    `title_5` TEXT NOT NULL,
    `text_3` TEXT NOT NULL,
    `text_4` TEXT NOT NULL,
    PRIMARY KEY (`id_egblockbuilder_items`, `id_lang`, `id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'egblockbuilder_items_shop` (
    `id_egblockbuilder_items` INT(11) NOT NULL,
    `id_shop` INT(11) NOT NULL,
    PRIMARY KEY (`id_egblockbuilder_items`, `id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'multiple_images` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_egblockbuilder_items` INT(11) NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `double_image` TEXT NOT NULL,
    `double_image_mobile` TEXT NOT NULL,
    `text_double` TEXT NOT NULL,
    `video` TEXT NOT NULL,
    `video_double` TEXT NOT NULL,
    `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
