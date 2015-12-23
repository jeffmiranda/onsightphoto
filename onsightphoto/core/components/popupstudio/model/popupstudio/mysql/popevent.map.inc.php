<?php
$xpdo_meta_map['popEvent']= array (
  'package' => 'popupstudio',
  'version' => '1.1',
  'table' => 'events',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => NULL,
    'default_inv_markup' => 0,
    'default_tax' => 0,
    'active' => 1,
    'date_created' => NULL,
    'last_modified' => 'CURRENT_TIMESTAMP',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'default_inv_markup' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '4,3',
      'attributes' => 'unsigned',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'default_tax' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '4,3',
      'attributes' => 'unsigned',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
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
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
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
    'EventGroup' => 
    array (
      'class' => 'popEventGroup',
      'local' => 'id',
      'foreign' => 'event_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
