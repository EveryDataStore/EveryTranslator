<?php
namespace EveryTranslator\Extension;

use SilverStripe\ORM\DataExtension;
use EveryTranslator\Helper\EveryTranslatorHelper;

class EveryTranslatorFormFieldSettingExtension extends DataExtension {
    
    public function onAfterWrite() {
        parent::onAfterWrite();
        if($this->owner->Title == 'label' || $this->owner->Title == 'placeholder' || $this->owner->Title == 'info'){
            EveryTranslatorHelper::createDefault($this->owner->Value);
        }
    }
}
