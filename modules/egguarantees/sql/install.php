<?php

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'egguarantees` (
    `id_egguarantees` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `subtitle` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255),
    `position` INT(11) UNSIGNED NOT NULL DEFAULT 0,
    `active` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id_egguarantees`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'egguarantees_logos` (
    `id_egguarantees_logos` INT(11) NOT NULL AUTO_INCREMENT,
    `id_egguarantees` INT(11) NOT NULL,
    `src` VARCHAR(255) NOT NULL,
    `alt` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id_egguarantees_logos`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (!Db::getInstance()->execute($query)) {
        return false;
    }
}

return true;