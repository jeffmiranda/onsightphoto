<?php
class popEventUpdateProcessor extends modObjectUpdateProcessor {

    public $classKey = 'popEvent';
    public $languageTopics = array('popupstudio:default');
    public $objectType = 'pop.popevent';
    
    public function beforeSet() {
    
		// Properly change markup and tax rates to decimals
		$markup = $this->getProperty('default_inv_markup');
		if (isset($markup)) {
			$markup = round($markup / 100, 3);
			$this->setProperty('default_inv_markup', $markup);
		}
		
		$tax = $this->getProperty('default_tax');
		if (isset($tax)) {
			$tax = round($tax / 100, 3);
			$this->setProperty('default_tax', $tax);
		}
    
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
        
}
return 'popEventUpdateProcessor';