<?php

namespace EveryTranslator\Extension;

use EveryTranslator\Helper\EveryTranslatorHelper;

class EveryTranslatorFormFieldExtension extends EveryTranslatorHelper {
    
    public static function getLabel($field) {
        $label = $field->Settings()->filter(array('Title' => 'label', 'FormFieldID' => $field->ID))->first()->Value;
        return EveryTranslatorHelper::_t($label);
    }
    
    public static function getPlaceholder($field) {
        $label = $field->Settings()->filter(array('Title' => 'placeholder', 'FormFieldID' => $field->ID))->first()->Value;
        return EveryTranslatorHelper::_t($label);
    }

}
