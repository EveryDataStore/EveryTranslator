<?php
namespace EveryTranslator\Extension;

use SilverStripe\ORM\DataExtension;
use EveryTranslator\Helper\EveryTranslatorHelper;


class EveryTranslatorRecordSetItemDataExtension extends DataExtension {

    public function onAfterWrite() {
        if(EveryTranslatorHelper::isTranslatableRecordSet($this->owner->RecordSetItem()->RecordSet()->Slug)){
            EveryTranslatorHelper::createDefault($this->owner->Value);
        }
        parent::onAfterWrite();
    }
}
