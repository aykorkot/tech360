<?php

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egguarantees`;';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'egguarantees_logos`;';

foreach ($sql as $query) {
    if (!Db::getInstance()->execute($query)) {
        return false;
    }
}

return true;