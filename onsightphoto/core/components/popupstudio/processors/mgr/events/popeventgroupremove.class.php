<?php
class popEventGroupRemoveProcessor extends modObjectRemoveProcessor {
    
    public $classKey = 'popEventGroup';
    public $languageTopics = array('popupstudio:default');
    public $objectType = 'pop.popeventgroup';
    
    
    public function beforeRemove() {
	    
	    // check if event group is linked to a manifest
        $manifests = $this->modx->getCount('popManifest',array('event_group_id' => $this->object->id));
        if ($manifests > 0) {
            return $this->modx->lexicon('pop.err_event_group_linked_manifest').' '.$this->object->name.' ('.$this->object->id.')';
        }
        // check if children are linked to a manifest
        $canRemove = $this->verifyChildren($this->object->Children, 'popManifest', 'pop.err_event_group_linked_manifest');
        if ($canRemove !== true) { return $canRemove; }
        
        // check if event group is linked to a photo group
        $groups = $this->modx->getCount('popGroup',array('event_group_id' => $this->object->id));
        if ($groups > 0) {
            return $this->modx->lexicon('pop.err_event_group_linked_group');
        }
        // check if children are linked to a photo group
        $canRemove = $this->verifyChildren($this->object->Children, 'popGroup', 'pop.err_event_group_linked_group');
        if ($canRemove !== true) { return $canRemove; }
        
        return true;
    }
    
    
    private function verifyChildren($children, $class, $lexErrorKey) {
	    if ($children) {
		    
		    foreach ($children as $child) {
			    
			    $count = $this->modx->getCount($class, array('event_group_id' => $child->id));
			    if ($count > 0) { return $this->modx->lexicon($lexErrorKey).' '.$child->name.' ('.$child->id.')'; }
			    
			    $canRemove = $this->verifyChildren($child->Children, $class, $lexErrorKey);
			    if ($canRemove !== true) { return $canRemove; }
			    
		    }
		    
	    }
	    
	    return true;
    }

}
return 'popEventGroupRemoveProcessor';