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
    public static function _t($title) {
        if(!is_string($title) || is_array(unserialize($title))) return $title;
        $trans = self::getMember() ? EveryTranslator::get()->filter(['Title' => $title, 'DataStoreID' => self::getCurrentDataStoreID(), 'Locale' => self::getMember()->Locale])->first() : null;
        if(!$trans){
            $trans = self::createDefault($title);
        }
        return $trans ? $trans->Value : $title;
    }

    /**
     * This function creates a new item as default for all languages excluding default_locale 
     * @param string $title
     */
    public static function createDefault($title) {
        if(!is_string($title) || is_array(unserialize($title))) return $title;
        $fl = Config::inst()->get('Frontend_Languages');
        foreach ($fl as $k => $v) {
            $t = EveryTranslator::get()->filter(['Title' => $title, 'Locale' => $k, 'DataStoreID' => EveryDataStoreHelper::getCurrentDataStoreID()])->first();
            if (!$t) {
                $newT = new EveryTranslator();
                $newT->Title = trim($title);
                $newT->Value = self::getMember()->Locale !== $k ? self::getGoogleTranslate(trim($title), substr($k,0,2)): trim($title);
                $newT->Locale = $k;
                $newT->DataStoreID = EveryDataStoreHelper::getCurrentDataStoreID();
                $newT->write();
            }
        }
    }
    
    /**
     * Returns true if SecordSet Translatable
     * @param string $recordSetSlug
     * @return boolean
     */
    public static function isTranslatableRecordSet($recordSetSlug){
         $translatableRecordSets = Config::inst()->get('translatableRecordSets');
         if($translatableRecordSets && in_array($recordSetSlug, $translatableRecordSets)){
             return true;
         }
    }
    
    /**
     * Translates the given label using the Google Translate API.
     * Prerequisite: A valid Google API key must be configured in
     * everydatastore/_config/everyTranslator.yml` under the appropriate key setting.
     *
     * @param string $title
     * @param string $target
     * @param string $src, default en
     * @return string
     */
    public static function getGoogleTranslate($title, $target, $src = 'en') {
        $googleTranslateKey = Config::inst()->get('EveryTranslator\Model\EveryTranslator', 'Google_Translate_Key');
        if (!empty($googleTranslateKey)) {
            $params = [
                'q' => $title,
                'target' => $target,
                'key' => $googleTranslateKey,
                'src' => $src
            ];

            $cURL = curl_init();
            curl_setopt($cURL, CURLOPT_URL, $endPoint = 'https://translation.googleapis.com/language/translate/v2/?' . http_build_query($params));
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($cURL));
            if ($res->data && isset($res->data->translations[0]) && $res->data->translations[0]->translatedText) {
                return strip_tags($res->data->translations[0]->translatedText);
            }
        }
        return $title;
    }

}
