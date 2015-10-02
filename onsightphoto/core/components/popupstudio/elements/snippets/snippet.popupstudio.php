<?php
$pop = $modx->getService('popupstudio','PopUpStudio',$modx->getOption('popupstudio.core_path',null,$modx->getOption('core_path').'components/popupstudio/').'model/popupstudio/',$scriptProperties);
if (!($pop instanceof PopUpStudio)) return '';

/* setup default properties */
$tpl = $modx->getOption('tpl',$scriptProperties,'rowTpl');
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
 
$output = '';

$c = $modx->newQuery('popEvent');
$c->sortby($sort,$dir);
$events = $modx->getCollection('popEvent', $c);

foreach ($events as $event) {
    $eventArray = $event->toArray();
    $output .= $pop->getChunk($tpl,$eventArray);
}

return $output;