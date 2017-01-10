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
    public function _construct()
    {
        // Setting up some basic class attributes
        $this->name = 'CodeChallengeModule';
        $this->tab = 'others';
        $this->version = '1.0';
        $this->author = 'David Varney <davidvarney@gmail.com>';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6.1.9');
        $this->bootstrap = true;

        // Initializing the Module _construct() method
        parent::_construct();

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

    }

    public function getContent()
    {

    }

    public function hookLeftColumn()
    {

    }

    public function hookRightColumn()
    {

    }

    public function hookHeader()
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
