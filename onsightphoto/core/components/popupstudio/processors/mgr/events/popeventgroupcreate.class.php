<?php
class popEventGroupCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'popEventGroup';
    public $languageTopics = array('popupstudio:default');
    public $objectType = 'pop.popeventgroup';
 
    public function beforeSet() {
    
    	// set the date created
		$date = date('Y-m-d');
		$this->setProperty('date_created',$date);
		
/*
		// set parent to null if not selected
		$parent = $this->getProperty('parent');
		$parent = ($parent == 0) ? null : $parent;
		$this->setProperty('parent', $parent);
*/
		
		/**
		 * Note that if a rank isn't specified the
		 * default value submitted is 0. So a new
		 * group without a specified rank will appear
		 * at the top of the list
		 */
				
		return parent::beforeSet();
    
    }
    
    public function beforeSave() {
        
        $eventId = $this->getProperty('event_id');
        $name = $this->getProperty('name');
		$parent = $this->getProperty('parent');
        $rank = $this->getProperty('rank');
        
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('pop.err_missing_event_group_name'));
        } else if ($this->doesAlreadyExist(array('name' => $name, 'event_id' => $eventId, 'parent' => $parent))) {
	        $this->addFieldError('name',$this->modx->lexicon('pop.err_ae_for_event'));
        } else if ($this->doesAlreadyExist(array('parent' => $parent, 'rank' => $rank, 'event_id' => $eventId))) {
		    /**
			 * if a group with the same rank and parent and event exists,
			 * adjust the sort order for all the groups in that parent
			 * with the same rank or higher
			 */

	        // get all the groups for that parent that have a bigger rank
	        $c = $this->modx->newQuery('popEventGroup');
            $c->where(array(
		       'parent' => $parent
		       ,'rank:>=' => $rank
	        ));
            $c->sortby('rank','DESC');
	        $groups = $this->modx->getCollection('popEventGroup', $c);
	        
	        // increment each group's rank by one so there are no conflicts
	        foreach($groups as $group) {
		        $rank = $group->get('rank');
		        $rank++;
		        $group->set('rank', $rank);
		        $group->save();
	        }
        }
        
        return parent::beforeSave();
        
    }
}
return 'popEventGroupCreateProcessor';