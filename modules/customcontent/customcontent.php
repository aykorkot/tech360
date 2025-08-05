<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class CustomContent extends Module
{
    public function __construct()
    {
        $this->name = 'customcontent';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'ConnectZone';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Custom Content');
        $this->description = $this->l('Permet d\'afficher du contenu personnalisé en front.');
    }

    public function install()
    {
        return parent::install() &&
               $this->installDb() &&
               $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        return parent::uninstall() &&
               $this->uninstallDb();
    }

    private function installDb()
    {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'custom_content` (
                `id_custom_content` INT AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(255),
                `description` TEXT,
                `button_link` VARCHAR(255),
                `image` VARCHAR(255)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
        ');
    }

    private function uninstallDb()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'custom_content`');
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminCustomContent'));
    }

    public function hookDisplayHome($params)
    {
        $contents = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'custom_content`');
        $this->context->smarty->assign([
            'custom_contents' => $contents,
            'base_dir' => _PS_BASE_URL_ . __PS_BASE_URI__,
        ]);
        return $this->display(__FILE__, 'views/templates/hook/displayCustomContent.tpl');
    }
}
