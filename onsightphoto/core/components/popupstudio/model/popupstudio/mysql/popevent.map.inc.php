<?php
$xpdo_meta_map['popEvent']= array (
  'package' => 'popupstudio',
  'version' => '1.0',
  'table' => 'event',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'default_inv_markup' => 1,
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
      'default' => '',
    ),
    'default_inv_markup' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '4,3',
      'attributes' => 'unsigned',
      'phptype' => 'float',
      'null' => false,
      'default' => 1,
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
      'phptype' => 'integer',
      'attributes' => 'unsigned',
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
);
