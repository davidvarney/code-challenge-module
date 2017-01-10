<?php
/**
 * A simple weather module made for the purpose of a 72 hour Code Challenge
 *
 * @author David Varney <davidvarney@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 */

if (!defined('_PS_VERSION_'))
    exit;

class CodeChallengeModule extends Module
{
    public function __construct()
    {
        // Setting up some basic class attributes
        $this->name = 'codechallengemodule';
        $this->tab = 'others';
        $this->version = '1.0';
        $this->author = 'David Varney <davidvarney@gmail.com>';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6.1.9');
        $this->bootstrap = true;

        // Initializing the Module _construct() method
        parent::__construct();

        // Now lets establish our module's text strings
        $this->displayName = $this->l('Code-Challenge-Module');
        $this->description = $this->l('Adds a weather widget to the front of our store based on an admin supplied zip code.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided.');
        }
    }

    public function install()
    {
        return parent::install() &&
               $this->installDB() &&
               $this->registerHook('leftColumn') &&
               $this->registerHook('header') &&
               $this->Configuration::updateValue('MYMODULE_NAME', 'Code-Challenge-Module');
    }

    public function installDB()
    {
        $return = true;
        $return &= Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'code_challenge_module` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `zip_code` VARCHAR(5) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
        );

        return $return;
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDB();
    }

    public function uninstallDB($drop_table = true)
    {
        $ret = true;
        if($drop_table)
            $ret &=  Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'code_challenge_module`');

        return $ret;
    }

    public function getContent()
    {

    }

    public function hookLeftColumn($params)
    {

    }

    public function hookRightColumn($params)
    {

    }

    public function hookHeader($params)
    {

    }

    public function renderForm()
    {

    }

    public function getConfigFieldsValues()
    {
        return array(
            //
        );
    }
}
