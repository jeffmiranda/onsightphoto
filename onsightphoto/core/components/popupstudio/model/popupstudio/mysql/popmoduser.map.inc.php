<?php
$xpdo_meta_map['popModUser']= array (
  'package' => 'popupstudio',
  'version' => '1.1',
  'extends' => 'modUser',
  'fields' => 
  array (
  ),
  'fieldMeta' => 
  array (
  ),
  'composites' => 
  array (
    'Manifest' => 
    array (
      'class' => 'popManifest',
      'local' => 'id',
      'foreign' => 'applied_by',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
