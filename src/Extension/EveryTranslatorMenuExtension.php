<?php
namespace EveryTranslator\Extension;

use SilverStripe\ORM\DataExtension;
use EveryTranslator\Helper\EveryTranslatorHelper;

class EveryTranslatorMenuExtension extends DataExtension {

    public function onAfterWrite() {
        parent::onAfterWrite();
        EveryTranslatorHelper::createDefault($this->owner->Title);
    }
    
}
