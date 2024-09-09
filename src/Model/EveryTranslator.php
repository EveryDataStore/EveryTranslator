<?php

namespace EveryTranslator\Model;

use EveryTranslator\Helper\EveryTranslatorHelper;
use EveryDataStore\Model\DataStore;;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Security\PermissionProvider;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;

class EveryTranslator extends DataObject implements PermissionProvider 
{
    private static $table_name = 'EveryTranslator';
    private static $singular_name = 'EveryTranslator';
    private static $plural_name = 'EveryTranslator';
    private static $db = [
        'Slug' => 'Varchar(110)',
        'Title' => 'Varchar(110)',
        'Value' => 'Text',
        'Locale' => 'Varchar(10)'
    ];
    
    private static $default_sort = "Title";
    private static $has_one = [
            'DataStore' => DataStore::class
    ];
    private static $has_many = [];
    private static $many_many= [];
    private static $summary_fields = [
        'Title',
        'Value',
        'Locale'
    ];
 
    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels(true);
        if(!empty(self::$summary_fields)){
           $labels = \EveryDataStore\Helper\EveryDataStoreHelper::getNiceFieldLabels($labels, __CLASS__, self::$summary_fields);
        }
        return $labels;
    }
    
    private static $searchable_fields = [
        'Title' => [
            'field' => TextField::class,
            'filter' => 'PartialMatchFilter',
        ],
        'Value' => [
            'field' => TextField::class,
            'filter' => 'PartialMatchFilter',
        ],
         'Locale' => [
            'field' => TextField::class,
            'filter' => 'PartialMatchFilter',
        ],
    ];
   
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab('Root.Main', ['DataStoreID']);
        $fields->addFieldToTab('Root.Main', HiddenField::create('Slug', 'Slug', $this->Slug));
        $fields->addFieldToTab('Root.Main', DropdownField::create('Locale', _t($this->owner->ClassName . '.LOCALE', 'Locale'), Config::inst()->get('Frontend_Languages'))->setEmptyString(_t('Global.SELECTONE', 'Select one')));
        $fields->addFieldToTab('Root.Main', TextField::create('Title', _t(__Class__ .'.TITLE', 'Title')));
        $fields->addFieldToTab('Root.Main', TextareaField::create('Value', _t(__Class__ .'.VALUE', 'Value')));
        return $fields;
    }
     
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        if (!$this->Slug) {
            $this->Slug = EveryTranslatorHelper::getAvailableSlug(__CLASS__);
        }
        
        if (!$this->DataStoreID) {
            $this->DataStoreID =  EveryTranslatorHelper::getCurrentDataStoreID();
        }
       
    }

    public function onAfterWrite() {
        parent::onAfterWrite();
    }

    public function onBeforeDelete() {
        parent::onBeforeDelete();
    }

    public function onAfterDelete() {
        parent::onAfterDelete();
    }
    
  

    /**
     * This function should return true if the current user can view an object
     * @see Permission code VIEW_CLASSSHORTNAME e.g. VIEW_MEMBER
     * @param Member $member The member whose permissions need checking. Defaults to the currently logged in user.
     * @return bool True if the the member is allowed to do the given action
     */
    public function canView($member = null) {
        return EveryTranslatorHelper::checkPermission(EveryTranslatorHelper::getNicePermissionCode("VIEW", $this));
    }

    /**
     * This function should return true if the current user can edit an object
     * @see Permission code VIEW_CLASSSHORTNAME e.g. EDIT_MEMBER
     * @param Member $member The member whose permissions need checking. Defaults to the currently logged in user.
     * @return bool True if the the member is allowed to do the given action
     */
    public function canEdit($member = null) {
        return EveryTranslatorHelper::checkPermission(EveryTranslatorHelper::getNicePermissionCode("EDIT", $this));
    }

    /**
     * This function should return true if the current user can delete an object
     * @see Permission code VIEW_CLASSSHORTNAME e.g. DELTETE_MEMBER
     * @param Member $member The member whose permissions need checking. Defaults to the currently logged in user.
     * @return bool True if the the member is allowed to do the given action
     */
    public function canDelete($member = null) {
        return EveryTranslatorHelper::checkPermission(EveryTranslatorHelper::getNicePermissionCode("DELETE", $this));
    }

    /**
     * This function should return true if the current user can create new object of this class.
     * @see Permission code VIEW_CLASSSHORTNAME e.g. CREATE_MEMBER
     * @param Member $member The member whose permissions need checking. Defaults to the currently logged in user.
     * @param array $context Context argument for canCreate()
     * @return bool True if the the member is allowed to do this action
     */
    public function canCreate($member = null, $context = []) {
        return EveryTranslatorHelper::checkPermission(EveryTranslatorHelper::getNicePermissionCode("CREATE", $this));
    }

    /**
     * Return a map of permission codes for the Dataobject and they can be mapped with Members, Groups or Roles
     * @return array 
     */
    public function providePermissions() {
        return array(
            EveryTranslatorHelper::getNicePermissionCode("CREATE", $this) => [
                'name' => _t('SilverStripe\Security\Permission.CREATE', "CREATE"),
                'category' => ClassInfo::shortname($this),
                'sort' => 1
            ],
            EveryTranslatorHelper::getNicePermissionCode("EDIT", $this) => [
                'name' => _t('SilverStripe\Security\Permission.EDIT', "EDIT"),
                'category' => ClassInfo::shortname($this),
                'sort' => 1
            ],
            EveryTranslatorHelper::getNicePermissionCode("VIEW", $this) => [
                'name' => _t('SilverStripe\Security\Permission.VIEW', "VIEW"),
                'category' => ClassInfo::shortname($this),
                'sort' => 1
            ],
            EveryTranslatorHelper::getNicePermissionCode("DELETE", $this) => [
                'name' => _t('SilverStripe\Security\Permission.DELETE', "DELETE"),
                'category' => ClassInfo::shortname($this),
                'sort' => 1
        ]);
    }
}
