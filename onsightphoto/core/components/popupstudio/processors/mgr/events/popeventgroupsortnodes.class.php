<?php
/**
 * Sorts the resource tree
 *
 * @param string $data The encoded tree data
 *
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
class popEventGroupSortNodes extends modProcessor {

    public $source;
    public $target;
    public $point;

    public function process() {

        // Get properties
        $data = urldecode($this->getProperty('data',''));
        if (empty($data)) $this->failure($this->modx->lexicon('invalid_data'));
        $data = $this->modx->fromJSON($data);
        if (empty($data)) $this->failure($this->modx->lexicon('invalid_data'));

        $target = $this->getProperty('target', '');
        $source = $this->getProperty('source', '');
        $point = $this->getProperty('point', '');
        error_log('Target: '.$target);
        error_log('Source: '.$source);
        error_log('Point: '.$point);
        if (empty ($target)) {
            return $this->failure('Target not set');
        }
        if (empty($source)) {
            return $this->failure('Source not set');
        }
        if (empty($point)) {
            return $this->failure('Point not set');
        }
        
        // Set the nodes
        $this->point = $point;
        $this->parseNodes($source, $target);
        
        // Time to sort!
		$sorted = $this->sort();
        if ($sorted !== true) return $this->failure($sorted);

		return $this->success();
		
    }
    
    
    public function parseNodes($source, $target) {

        $this->source = $this->modx->getObject('popEventGroup', $source);
        $this->target = $this->modx->getObject('popEventGroup', $target);

    }


	public function sort() {
		
        $lastRank = $this->target->rank;

        if ($this->point == 'above') {
            return $this->moveEventGroupAbove($lastRank);
        }

        if ($this->point == 'below') {
            return $this->moveEventGroupBelow($lastRank);
        }

        if ($this->point == 'append') {
            return $this->appendEventGroup();
        }

        return false;
    }

    public function moveEventGroupAbove($lastRank){

		$status = $this->moveAffectedEventGroups($lastRank);
        if ($status !== true) { return $status; }
        
        $this->source->set('rank', $lastRank);
        $this->source->set('parent', $this->target->parent);
        $this->target->set('rank', $lastRank + 1);

		if (!$this->target->save()) { return false; }
		if (!$this->source->save()) { return false; }
		
		return true;
        
    }

    public function moveEventGroupBelow($lastRank) {
        
        $status = $this->moveAffectedEventGroups($lastRank);
        if ($status !== true) { return $status; }
        
        $this->source->set('rank', $lastRank + 1);
        $this->source->set('parent', $this->target->parent);
        
        if (!$this->source->save()) { return false; }

        return true;
    }

    public function appendEventGroup() {
        $c = $this->modx->newQuery('popEventGroup');
        $c->where(array(
            'parent' => $this->target->id
            ,'event_id' => $this->target->event_id
        ));
        $c->sortby('rank', 'DESC');
        $c->limit(1);

        $lastEventGroup = $this->modx->getObject('popEventGroup', $c);

        if ($lastEventGroup) {
            $this->source->set('rank', $lastEventGroup->rank + 1);
        } else {
            $this->source->set('rank', 0);
        }

        $this->source->set('parent', $this->target->id);

        if (!$this->source->save()) { return false; }

        return true;
    }

    public function moveAffectedEventGroups($lastRank){
        
        $c = $this->modx->newQuery('popEventGroup');
        $c->where(array(
            'rank:>' => $lastRank
            ,'parent' => $this->target->parent
            ,'event_id' => $this->target->event_id
        ));
        $c->sortby('rank', 'DESC');

        $eventGroupsToSort = $this->modx->getIterator('popEventGroup', $c);
        //$lastRank = $lastRank + 1;

        foreach ($eventGroupsToSort as $eventGroup) {
            $eventGroup->set('rank', $eventGroup->rank + 1);
            if (!$eventGroup->save()) {
	            return $this->modx->lexicon('pop.err_saving_event_group');
            } 
            //$lastRank++;
        }

        return true;
    }

}
return 'popEventGroupSortNodes';
