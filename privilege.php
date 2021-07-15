<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Privilege extends Module
{
    public function __construct()
    {
        $this->name = 'privilege';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'Stéphane Burlet';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => '1.7.99',
        ];
        $this->bootstrap = true;

        parent::__construct();
/* Gestion des privilèges */
        $this->displayName = $this->l('Manage privileges');
        /* Créer un groupe "clients privilégiés", "professionnels", "commerciaux" et leur attribuer des privilèges*/
        $this->description = $this->l('create groups "clients privilégiés", "professionnels", "commerciaux" and affect them privileges');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

/*        if (!Configuration::get('SBUMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }*/
    }
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        $sql = "ALTER TABLE `"._DB_PREFIX_."customer` ADD `privilege_code` VARCHAR(255) NULL AFTER `birthday`";
            
               if(!$result=Db::getInstance()->Execute($sql))
               return false;


        return (parent::install()
            && $this->registerHook('additionalCustomerFormFields')
            && $this->registerHook('displayAdminCustomers')
            && $this->registerHook('actionCategoryAdd')
            && $this->registerHook('actionCategoryFormBuilderModifier')
            && $this->registerHook('actionAfterUpdateCustomerFormHandler')
            && $this->registerHook('actionAfterCreateCategoryFormHandler')
            && $this->registerHook('actionAfterUpdateCategoryFormHandler'));
//            && $this->registerHook('leftColumn')
  //          && $this->registerHook('header'));
//            && Configuration::updateValue('SBUMODULE_NAME', 'my friend'));
    }

    


    public function uninstall()
    {
        $sql = "ALTER TABLE `"._DB_PREFIX_."customer` DROP `privilege_code`";
            
               if(!$result=Db::getInstance()->Execute($sql))
               return false;

        return (parent::uninstall());
            //&& Configuration::deleteByName('SBUMODULE_NAME'));
    }

    public function hookdisplayAdminCustomers ($params) {
        $a="displayAdminCustomers - ";
        $a=$a.print_r($params,true);
        error_log($a);
        return true;
    }


    /**
     * Ajout d'un champ client supplémentaire en FO
     * @param type $params
     */
    public function hookAdditionalCustomerFormFields($params) {
 
        $a=print_r($params,true);
        error_log($a);
        /*echo "<pre>";
        print_r($params);
        echo "</pre>";*/
        return [
                    (new FormField)
                    ->setName('privilege_code')
                    ->setType('text')
                    //->setRequired(true) Décommenter pour rendre obligatoire
                    ->setLabel($this->l('Privilege code'))
        ];
    }


    public function hookActionCategoryAdd ($params) {
        echo "actionCategoryAdd";
        $a=print_r($params,true);
        error_log($a);
        return true;
    }


    public function hookActionAfterUpdateCustomerFormHandler ($params) {
        $a="actionAfterUpdateCustomerFormHandler - ";
        $a=$a.print_r($params,true);
        error_log($a);
        $formBuilder = $params['form_builder'];
        error_log("*********************");
        error_log(print_r($formBuilder));
        
 
        //Ajout de notre champ spécifique
        $formBuilder->add($this->name . '_newfield1',
            //Cf génériques symonfy https://symfony.com/doc/current/reference/forms/types.html
            // et spécificiques prestashop https://devdocs.prestashop.com/1.7/development/components/form/types-reference/
            \Symfony\Component\Form\Extension\Core\Type\TextType::class,
            [
                'label' => $this->l('Custom field 1'), //Label du champ
                'required' => false, //Requis ou non
                'constraints' => [ //Contraintes du champs
                    //cf. génériques symfony : https://symfony.com/doc/current/reference/constraints.html
                    // Ou vous pouvez écrire la votre cf. https://symfony.com/doc/current/validation/custom_constraint.html
                    new \Symfony\Component\Validator\Constraints\Length([
                        'max' => 20,
                        'maxMessage' => $this->l('Max caracters allowed : 20'),
                    ]),
                ],
                //La valeur peut être setée ici
                'data' => 'test valeur', //Valeur du champ
                // Texte d'aide
                'help' => $this->l('help text')
            ]
        );
 
        //Ou surchargée ici
        $params['data'][$this->name . '_newfield1'] = 'Custom value 1';
 
      //Ajout d'un champ langue
        $formBuilder->add($this->name . '_newfield_lang',
            // cf. https://devdocs.prestashop.com/1.7/development/components/form/types-reference/
            \PrestaShopBundle\Form\Admin\Type\TranslatableType::class,
            [
                'label' => $this->l('Custom field Lang'), //Label du champ
                'required' => false, //Requis ou non
                'type' => \Symfony\Component\Form\Extension\Core\Type\TextType::class // OU TextAreaType::class
            ]
        );
        //Définition des données du champ langue
        $languages = Language::getLanguages(true);
        foreach ( $languages as $lang){
            $params['data'][$this->name . '_newfield_lang'][$lang['id_lang']] = 'Custom value for lang '.$lang['iso_code'];
        }
 
        //On peut également changer facilement la donnée de n'importe quel autre champ du formulaire
        $params['data']['active'] = false;
 
        //Il faut bien penser à mettre cette ligne pour mettre à jour les données au formulaire
        $formBuilder->setData($params['data']);
        
    }
    public function hookActionAfterCreateCategoryFormHandler ($params) {
        echo "actionAfterCreateCategoryFormHandler";
        $a=print_r($params,true);
        error_log($a);
    }
    
    public function hookActionAfterUpdateCategoryFormHandler ($params) {
        echo "actionAfterUpdateCategoryFormHandler";
        $a=print_r($params,true);
        error_log($a);
    }
    










    public function hookActionCategoryFormBuilderModifier(array $params)
    {
        $a="ActionCategoryFormBuilderModifier - ";
        //$a=$a.print_r($params,true);
        //error_log(print_r($params,true));
        //Récupération du form builder
        /** @var \Symfony\Component\Form\FormBuilder $formBuilder */
        $formBuilder = $params['form_builder'];
 
 
        //Ajout de notre champ spécifique
        $formBuilder->add($this->name . '_newfield1',
            //Cf génériques symonfy https://symfony.com/doc/current/reference/forms/types.html
            // et spécificiques prestashop https://devdocs.prestashop.com/1.7/development/components/form/types-reference/
            \Symfony\Component\Form\Extension\Core\Type\TextType::class,
            [
                'label' => $this->l('Custom field 1'), //Label du champ
                'required' => false, //Requis ou non
                'constraints' => [ //Contraintes du champs
                    //cf. génériques symfony : https://symfony.com/doc/current/reference/constraints.html
                    // Ou vous pouvez écrire la votre cf. https://symfony.com/doc/current/validation/custom_constraint.html
                    new \Symfony\Component\Validator\Constraints\Length([
                        'max' => 20,
                        'maxMessage' => $this->l('Max caracters allowed : 20'),
                    ]),
                ],
                //La valeur peut être setée ici
                'data' => 'test valeur', //Valeur du champ
                // Texte d'aide
                'help' => $this->l('help text')
            ]
        );
 
        //Ou surchargée ici
        $params['data'][$this->name . '_newfield1'] = 'Custom value 1';
 
      //Ajout d'un champ langue
        $formBuilder->add($this->name . '_newfield_lang',
            // cf. https://devdocs.prestashop.com/1.7/development/components/form/types-reference/
            \PrestaShopBundle\Form\Admin\Type\TranslatableType::class,
            [
                'label' => $this->l('Custom field Lang'), //Label du champ
                'required' => false, //Requis ou non
                'type' => \Symfony\Component\Form\Extension\Core\Type\TextType::class // OU TextAreaType::class
            ]
        );
        //Définition des données du champ langue
        $languages = Language::getLanguages(true);
        foreach ( $languages as $lang){
            $params['data'][$this->name . '_newfield_lang'][$lang['id_lang']] = 'Custom value for lang '.$lang['iso_code'];
        }
 
        //On peut également changer facilement la donnée de n'importe quel autre champ du formulaire
        $params['data']['active'] = false;
 
        //Il faut bien penser à mettre cette ligne pour mettre à jour les données au formulaire
        $formBuilder->setData($params['data']);
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $myModuleName = strval(Tools::getValue('SBUMODULE_NAME'));

            if (
                !$myModuleName ||
                empty($myModuleName) ||
                !Validate::isGenericName($myModuleName)
            ) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('SBUMODULE_NAME', $myModuleName);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        return $output . $this->displayForm();
    }


    public function displayForm()
    {
        // Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Configuration value'),
                    'name' => 'SBUMODULE_NAME',
                    'size' => 20,
                    'required' => true
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];

        // Load current value
        $helper->fields_value['SBUMODULE_NAME'] = Tools::getValue('SBUMODULE_NAME', Configuration::get('SBUMODULE_NAME'));

        return $helper->generateForm($fieldsForm);
    }

    public function hookDisplayLeftColumn($params)
    {
        $this->context->smarty->assign([
            'my_module_name' => Configuration::get('SBUMODULE_NAME'),
            'my_module_link' => $this->context->link->getModuleLink('sbumodule', 'display')
        ]);

        return $this->display(__FILE__, 'sbumodule.tpl');
    }

    public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            'sbumodule-style',
            $this->_path . 'views/css/sbumodule2.css',
            [
                'media' => 'all',
                'priority' => 1000,
            ]
        );

        $this->context->controller->registerJavascript(
            'sbumodule-javascript',
            $this->_path . 'views/js/sbumodule.js',
            [
                'position' => 'bottom',
                'priority' => 1000,
            ]
        );
    }
}
