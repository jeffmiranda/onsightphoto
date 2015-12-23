<?php
class popEventCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'popEvent';
    public $languageTopics = array('popupstudio:default');
    public $objectType = 'pop.popevent';
 
    public function beforeSet() {
    
    	// set the date created
		$date = date('Y-m-d');
		$this->setProperty('date_created',$date);
		
		// Properly change markup and tax rates to decimals
		$markup = $this->getProperty('default_inv_markup');
		$markup = round($markup / 100, 3);
		$this->setProperty('default_inv_markup', $markup);
		
		$tax = $this->getProperty('default_tax');
		$tax = round($tax / 100, 3);
		$this->setProperty('default_tax', $tax);
		
		return parent::beforeSet();
    
    }
    
    public function beforeSave() {
        
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('pop.err_missing_event_name'));
        }
        
        $markup = $this->getProperty('default_inv_markup');
        if (empty($markup)) {
            $this->addFieldError('default_inv_markup',$this->modx->lexicon('pop.err_missing_inv_markup'));
        }
        
        $tax = $this->getProperty('default_tax');
        if (empty($tax)) {
            $this->addFieldError('default_tax',$this->modx->lexicon('pop.err_missing_default_tax'));
        }
        
        return parent::beforeSave();
        
    }
}
return 'popEventCreateProcessor';