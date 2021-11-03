<?php

final class RequirementSchemaSpecs
  extends PhabricatorConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new Requirement());
  }

}
