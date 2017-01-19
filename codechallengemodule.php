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
        $this->author = 'David Varney - davidvarney@gmail.com';
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
               $this->registerHook('rightColumn') &&
               Configuration::updateValue('MYMODULE_NAME', 'Code-Challenge-Module') &&
               Configuration::updateValue('WEATHER_API_URL', 'http://api.openweathermap.org/data/2.5/weather?zip=%s,us&units=imperial&appid=43f524120ab4bdfdad18443975d734ed') &&
               Configuration::updateValue('WEATHER_API_IMAGE_URL', 'http://openweathermap.org/themes/openweathermap/assets/vendor/owm/img/widgets/%s.png') &&
               Configuration::updateValue('ZIP_CODE_VALIDATOR_URL', 'http://api.zippopotam.us/us/%s');
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
        $output = '';

        if (Tools::isSubmit('submitCodeChallengeModule')) {
            $errors = array();
            $zip_code_from_form = Tools::getValue('ZIP_CODE');
            $url = sprintf(Configuration::get('WEATHER_API_URL'), $zip_code_from_form);
            $zip_code_url = sprintf(Configuration::get('ZIP_CODE_VALIDATOR_URL'), $zip_code_from_form);
            $zip_code_from_api = Tools::jsonDecode(Tools::file_get_contents($zip_code_url));
            $json_zip_code_label = "post code";

            if (!$zip_code_from_api || !Validate::isZipCodeFormat($zip_code_from_api->$json_zip_code_label)) {
                $errors[] = $this->l('Invalid zip code');
            } elseif (!Tools::jsonDecode(Tools::file_get_contents($url))) {
                $errors[] = $this->l('Weather API call returned NULL');
            }

            if (!sizeof($errors)) {
                // Lets update the DB
                $zip_code_get_sql = 'SELECT COUNT (*) FROM `'._DB_PREFIX_.'code_challenge_module` ORDER BY `id` ASC';
                $zip_code_from_db = Db::getInstance()->executeS($zip_code_get_sql);
                if (count($zip_code_from_db) > 0) {
                    // Since we only want one value in the DB at a time we're
                    // going to go ahead and just delete everything and reset the table
                    Db::getInstance()->execute('TRUNCATE `'._DB_PREFIX_.'code_challenge_module`');
                }
                // Now lets insert the new zip code that was given to us by the user
                Db::getInstance()->insert('code_challenge_module', array('zip_code' => $zip_code_from_form));

                $output .= $this->displayConfirmation($this->l('Settings updated'));
            } else {
                $output .= $this->displayError(implode('<br />', $errors));
            }
        }

        return $output.$this->renderForm();
    }

    public function hookLeftColumn($params)
    {
        $cacheId = $this->getCacheId($this->name.'-'.date("YmdH"));
        if (!$this->isCached('codechallengemodule.tpl', $cacheId))
        {
            // Getting data
            $zip_code_sql = 'SELECT * FROM `'._DB_PREFIX_.'code_challenge_module`';
            $zip_code_result = Db::getInstance()->executeS($zip_code_sql);
            $url = strval(sprintf(Configuration::get('WEATHER_API_URL'), current($zip_code_result)['zip_code']));
            $image_url = '';
            $current_temp = '';
            $high_temp = '';
            $low_temp = '';
            $humidity = '';
            $wind_speed = '';
            $city_name = '';
            $main_condition = '';
            $title = 'Current Weather';
            // Lets attempt to get our JSON result because we need it to properly construct the image url
            if ($weather_json = Tools::jsonDecode(Tools::file_get_contents($url))) {
                $image_url = strval(sprintf(Configuration::get('WEATHER_API_IMAGE_URL'), $weather_json->weather[0]->icon));
                $current_temp = strval($weather_json->main->temp);
                $high_temp = strval($weather_json->main->temp_max);
                $low_temp = strval($weather_json->main->temp_min);
                $humidity = strval($weather_json->main->humidity);
                $wind_speed = strval($weather_json->wind->speed);
                $city_name = strval($weather_json->name);
                $main_condition = strval($weather_json->weather[0]->main);
            }

            // Display smarty
            $this->smarty->assign(array(
                'title'             => ($title ? $title : $this->l('Code-Challenge-Module')),
                'image_url'         => $image_url,
                'current_temp'      => round((float)$current_temp, 1),
                'high_temp'         => $high_temp,
                'low_temp'          => $low_temp,
                'humidity'          => $humidity,
                'wind_speed'        => $wind_speed,
                'city_name'         => $city_name,
                'main_condition'    => $main_condition,
            ));
        }

        // Add our CSS file here
        $this->context->controller->addCSS($this->_path.'css/codechallengemodule.css', 'all');

        return $this->display(__FILE__, 'codechallengemodule.tpl', $cacheId);
    }

    public function hookRightColumn($params)
    {
        return $this->hookLeftColumn($params);
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Zip Code'),
                        'name' => 'ZIP_CODE',
                        'desc' => $this->l('Add the zip code for the location you wish to view weather data from.'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCodeChallengeModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $zip_code_sql = 'SELECT * FROM `'._DB_PREFIX_.'code_challenge_module` ORDER BY `id` ASC';
        $existing_zip_code = Db::getInstance()->executeS($zip_code_sql);
        $existing_zip_code_value = '';
        if (count($existing_zip_code) > 0) {
            $existing_zip_code_value = current($existing_zip_code)['zip_code'];
        }

        return array(
            'ZIP_CODE' => Tools::getValue('ZIP_CODE', $existing_zip_code_value),
        );
    }
}
