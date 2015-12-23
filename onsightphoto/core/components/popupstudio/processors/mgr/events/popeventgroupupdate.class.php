<?php
class popEventGroupUpdateProcessor extends modObjectUpdateProcessor {

    public $classKey = 'popEventGroup';
    public $languageTopics = array('popupstudio:default');
    public $objectType = 'pop.popeventgroup';
    
    public $activeState = true;
    
    public function beforeSet() {
    
    	// retain current active state of object for use after save
    	$this->activeState = $this->object->get('active');
    
    	// toggle active if toggleActive is set
        $toggleActive = $this->getProperty('toggleActive');
        if (isset($toggleActive)) {
	        if ($this->object->get('active') == 1) {
		        $this->setProperty('active', 0);
	        } else {
		        $this->setProperty('active', 1);
	        }
        }
        
        return parent::beforeSet();
    }
    
    public function afterSave() {
	    
	    if ($this->activeState != $this->object->get('active')) {
		    $this->_syncChildrenActiveState();
		}
	    
    }
    
    private function _syncChildrenActiveState() {
	    
		$children = $this->object->getMany('Children');
		if ($children) {
			$state = $this->object->get('active');
			
			foreach ($children as $child) {
				$child->set('active', $state);
				$this->_syncGrandChildren($child->Children);
			}
			
			$this->object->save();
		}
	    
    }
    
    private function _syncGrandChildren($children) {
	    
	    if ($children) {
			$state = $this->object->get('active');    
			
			foreach ($children as $child) {
				$child->set('active', $state);
				$this->_syncGrandChildren($child->Children);
			}

	    }
	    
    }
        
}
return 'popEventGroupUpdateProcessor';