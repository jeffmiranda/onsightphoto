<?php
$xpdo_meta_map['popEventGroup']= array (
  'package' => 'popupstudio',
  'version' => '1.1',
  'table' => 'event_groups',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'event_id' => NULL,
    'parent' => 0,
    'name' => NULL,
    'rank' => NULL,
    'active' => 1,
    'date_created' => NULL,
    'last_modified' => 'CURRENT_TIMESTAMP',
  ),
  'fieldMeta' => 
  array (
    'event_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'date_created' => 
    array (
      'dbtype' => 'date',
      'phptype' => 'date',
      'null' => false,
    ),
    'last_modified' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
      'extra' => 'on update current_timestamp',
    ),
  ),
  'indexes' => 
  array (
    'eventparentrank' => 
    array (
      'alias' => 'eventparentrank',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'event_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'parent' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'rank' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'Children' => 
    array (
      'class' => 'popEventGroup',
      'local' => 'id',
      'foreign' => 'parent',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Parent' => 
    array (
      'class' => 'popEventGroup',
      'local' => 'parent',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Event' => 
    array (
      'class' => 'popEvent',
      'local' => 'event_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
