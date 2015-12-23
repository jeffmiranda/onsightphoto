<?php
require_once (dirname(__FILE__).'/popeventupdate.class.php');
class popEventUpdateFromGridProcessor extends popEventUpdateProcessor {

    public $classKey = 'popEvent';
    public $languageTopics = array('popupstudio:default');
    public $objectType = 'pop.popevent';
    
    public function initialize() {
    
	    $data = $this->getProperty('data');
	    if (!isset($data)) {
	    	$this->modx->log(modX::LOG_LEVEL_ERROR, 'The $data variable is not set when trying to update Event from grid.');
	    	return $this->modx->lexicon('pop.err_missing_data');
	    }
	    $data = $this->modx->fromJSON($data);
	    if (empty($data)) {
	    	$this->modx->log(modX::LOG_LEVEL_ERROR, 'The $data variable is empty after running fromJSON when trying to update Event from grid.');
	    	return $this->modx->lexicon('pop.err_missing_data');
	    }
	    $this->setProperties($data);
	    $this->unsetProperty('data');
	    return parent::initialize();
	    
    }
        
}
return 'popEventUpdateFromGridProcessor';