<?php
/**
 * 2020  (c)  Egio digital
 *
 * MODULE EgBanner
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */

$sql = array();


/* Information on content EG Banner */
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eg_bannerslider` (
        `id_eg_bannerslider` int(10) unsigned NOT NULL AUTO_INCREMENT,  
        `position` int(10) unsigned NOT NULL DEFAULT 0,
        `id_category` int(10) unsigned NOT NULL, 
        `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
        `type_video` varchar(255) NOT NULL,
        `video_vimeo` varchar(255) NOT NULL,
        PRIMARY KEY (`id_eg_bannerslider`)
    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;';

/* Localized EG Banner infos */
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eg_bannerslider_lang` (
        `id_eg_bannerslider` int(10) unsigned NOT NULL, 
        `id_lang` int(10) unsigned NOT NULL, 
        `id_shop` int(10) unsigned NOT NULL DEFAULT 1,
        `image` varchar(255) NOT NULL,
        `image_hover` varchar(255) NOT NULL,
        `link` varchar(255) NOT NULL, 
        `title` varchar(128) NOT NULL, 
        PRIMARY KEY (`id_eg_bannerslider`, `id_shop`, `id_lang`)
    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;';

/* Structure table eg_bannerslider shop */
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eg_bannerslider_shop` (
	`id_eg_bannerslider` int(10) unsigned NOT NULL, 
	`id_shop` int(10) unsigned NOT NULL ,
	PRIMARY KEY (`id_eg_bannerslider`, `id_shop`), 
	KEY `id_shop` (`id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;';


foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
