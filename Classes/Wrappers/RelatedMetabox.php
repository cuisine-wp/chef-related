<?php

namespace ChefRelated\Wrappers;

class RelatedMetabox extends Wrapper {

    /**
     * Return the igniter service key responsible for the Walker class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'relatedmetabox';
    }

}
