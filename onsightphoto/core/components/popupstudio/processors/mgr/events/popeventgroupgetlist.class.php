<?php
/**
 * Grabs a list of Event Groups.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to category.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 */
class popEventGroupGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'popEventGroup';
    public $languageTopics = array('popupstudio:default');
    public $defaultSortField = 'name';

    public function initialize() {
        
        $this->setDefaultProperties(array(
            'active' => 1
            ,'event_id' => 0
        ));

        return parent::initialize();
    }

    public function iterate(array $data) {
        $list = array();
        $list = $this->beforeIteration($list);

        foreach ($data['results'] as $group) {

            $groupArray = $group->toArray();
            $groupArray['name'] = $group->get('name');

            $list[] = $groupArray;

            $this->includeSubGroups($list, $group->Children, $groupArray['name']);
        }

        $list = $this->afterIteration($list);
        
        return $list;
    }

    public function includeSubGroups(&$list, $children, $nestedName){
        if ($children) {

            foreach ($children as $child) {

                $groupArray = $child->toArray();
                $groupArray['name'] = $nestedName . ' — ' . $child->get('name');

                $list[] = $groupArray;

                $this->includeSubGroups($list, $child->Children, $groupArray['name']);
            }            
        }
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {

		/**
		 * parent must be null to get the first level of groups.
		 * iterate will then get the children
		 */
		$event_id = $this->getProperty('event_id', 0);
		$active = $this->getProperty('active', 1);
		if (!empty($active)) {
	        $c->where(array(
		        'event_id' => $event_id
		        ,'parent' => 0
	            ,'active' => $active
	        ));
	    }
	    
        return $c;
    }


    public function prepareQueryAfterCount(xPDOQuery $c) {
        if ($this->getProperty('sort') == 'name') {
            $c->sortby('parent',$this->getProperty('dir','ASC'));
        }

        return $c;
    }

}
return 'popEventGroupGetListProcessor';
