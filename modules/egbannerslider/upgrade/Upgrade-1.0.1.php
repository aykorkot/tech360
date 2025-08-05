<?php
/**
 * 2019 (c) Egio Digital
 *
 * MODULE EgAdvancedCms
 *
 * @author    Egio Digital
 * @copyright Copyright (c) , Egio Digital
 * @license   Commercial
 * @version    1.0.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_1($module)
{
    return Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'eg_bannerslider` ADD `type_video` varchar(255) NOT NULL');
}
