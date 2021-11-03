<?php

class RequirementDAO extends PhabricatorLiskDAO
{

  public function getTableName()
  {
    return "requirements_requirement";
  }

  public function getApplicationName()
  {
    return 'requirements';
  }

  protected function getConfiguration()
  {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'properties' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'ownerPHID' => 'phid?',
        'status' => 'text64',
        'priority' => 'uint32',
        'title' => 'sort',
        'description' => 'text',
        'mailKey' => 'bytes20',
        'ownerOrdering' => 'text64?',
        'originalEmailSource' => 'text255?',
        'subpriority' => 'double',
        'points' => 'double?',
        'bridgedObjectPHID' => 'phid?',
        'subtype' => 'text64',
        'closedEpoch' => 'epoch?',
        'closerPHID' => 'phid?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_phid' => null,
        'phid' => array(
          'columns' => array('phid'),
          'unique' => true,
        ),
        'priority' => array(
          'columns' => array('priority', 'status'),
        ),
        'status' => array(
          'columns' => array('status'),
        ),
        'ownerPHID' => array(
          'columns' => array('ownerPHID', 'status'),
        ),
        'authorPHID' => array(
          'columns' => array('authorPHID', 'status'),
        ),
        'ownerOrdering' => array(
          'columns' => array('ownerOrdering'),
        ),
        'priority_2' => array(
          'columns' => array('priority', 'subpriority'),
        ),
        'key_dateCreated' => array(
          'columns' => array('dateCreated'),
        ),
        'key_dateModified' => array(
          'columns' => array('dateModified'),
        ),
        'key_title' => array(
          'columns' => array('title(64)'),
        ),
        'key_bridgedobject' => array(
          'columns' => array('bridgedObjectPHID'),
          'unique' => true,
        ),
        'key_subtype' => array(
          'columns' => array('subtype'),
        ),
        'key_closed' => array(
          'columns' => array('closedEpoch'),
        ),
        'key_closer' => array(
          'columns' => array('closerPHID', 'closedEpoch'),
        ),
      ),
    ) + parent::getConfiguration();
  }
}
