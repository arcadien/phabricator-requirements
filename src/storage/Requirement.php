<?php

final class Requirement extends ManiphestDAO
implements
// PhabricatorSubscribableInterface,
PhabricatorMarkupInterface,
// PhabricatorPolicyInterface,
// PhabricatorTokenReceiverInterface,
// PhabricatorFlaggableInterface,
PhabricatorMentionableInterface,
PhrequentTrackableInterface
// PhabricatorCustomFieldInterface,
// PhabricatorDestructibleInterface,
// PhabricatorApplicationTransactionInterface,
// PhabricatorProjectInterface,
// PhabricatorSpacesInterface,
// PhabricatorConduitResultInterface,
// PhabricatorFulltextInterface,
// PhabricatorFerretInterface,
// DoorkeeperBridgedObjectInterface,
// PhabricatorEditEngineSubtypeInterface,
// PhabricatorEditEngineLockableInterface,
// PhabricatorEditEngineMFAInterface,
// PhabricatorPolicyCodexInterface,
// PhabricatorUnlockableInterface 
{

    const MARKUP_FIELD_DESCRIPTION = 'markup:desc';

    protected $authorPHID;
    protected $ownerPHID;




    /* -(  Markup Interface  )--------------------------------------------------- */


  /**
   * @task markup
   */
  public function getMarkupFieldKey($field) {
    $content = $this->getMarkupText($field);
    return PhabricatorMarkupEngine::digestRemarkupContent($this, $content);
  }


  /**
   * @task markup
   */
  public function getMarkupText($field) {
    return $this->getDescription();
  }


  /**
   * @task markup
   */
  public function newMarkupEngine($field) {
    return PhabricatorMarkupEngine::newManiphestMarkupEngine();
  }


  /**
   * @task markup
   */
  public function didMarkupText(
    $field,
    $output,
    PhutilMarkupEngine $engine) {
    return $output;
  }


  /**
   * @task markup
   */
  public function shouldUseMarkupCache($field) {
    return (bool)$this->getID();
  }
}