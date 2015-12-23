<?php
class popEventGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'popEvent';
    public $languageTopics = array('popupstudio:default');
    public $defaultSortField = 'name';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'pop.popevent';
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
	    	    
	    $query = $this->getProperty('query');
	    if (!empty($query)) {
	        $c->where(array(
	            'popEvent.name:LIKE' => '%'.$query.'%'
	        ));
	    }
	    
	    // if active parameter exists and equals 1,
		// then only get the active records
		$active = $this->getProperty('active');
		if (!empty($active)) {
	        $c->where(array(
	            'popEvent.active' => $active
	        ));
	    }
	    
		//$c->prepare();
		//$this->modx->log(1,print_r('SQL Statement: ' . $c->toSQL(),true));
	    return $c;
	    
	}

    public function prepareRow(xPDOObject $object) {
	
        $row = $object->toArray();
        //$this->modx->log(1,print_r($ta,true));
        
        // prepare markup
        $markup = $row['default_inv_markup'] * 100;
        $row['default_inv_markup'] = $markup;
        
        // prepare tax
        $tax = $row['default_tax'] * 100;
        $row['default_tax'] = $tax;
        
        return $row;
        
    }
    
}
return 'popEventGetListProcessor';