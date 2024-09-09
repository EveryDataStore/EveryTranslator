<?php
namespace EveryTranslator\Helper;

use SilverStripe\Core\Config\Config;
use EveryDataStore\Helper\EveryDataStoreHelper;
use EveryTranslator\Model\EveryTranslator;

/** EveryDataStore/EveryTranslator v1.0
 * 
 * This class defines translation function
 * 
 */

class EveryTranslatorHelper extends EveryDataStoreHelper {
    
    /**
     * This function localizes the given string in the current dataStore replaces its value
     * @param string $title
     * @return string
     */
    public static function _t($title){
        $trans = self::getMember() ? EveryTranslator::get()->filter(['Title' => $title, 'DataStoreID' => self::getCurrentDataStoreID(), 'Locale' => self::getMember()->Locale])->first(): null;
	return $trans ? $trans->Value : $title;
    }
    
    /**
     * This function creates a new item as default for all languages excluding default_locale 
     * @param string $title
     */

    public static function createDefault($title) {
        $fl = Config::inst()->get('Frontend_Languages');      
        foreach ($fl as $k => $v) {
                $t = EveryTranslator::get()->filter(['Title' => $title, 'Locale' => $k, 'DataStoreID' => EveryDataStoreHelper::getCurrentDataStoreID()])->first();
                if (!$t) {
                    $newT = new EveryTranslator();
                    $newT->Title = trim($title);
                    $newT->Value = trim($title);
                    $newT->Locale = $k;
                    $newT->DataStoreID = EveryDataStoreHelper::getCurrentDataStoreID();
                    $newT->write();
                }
            }
        }
}
