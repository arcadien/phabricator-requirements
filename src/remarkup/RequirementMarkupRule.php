<?php

final class ManiphestRemarkupRule extends PhabricatorObjectRemarkupRule {

protected function getObjectNamePrefix() {
  return 'R';
}

protected function loadObjects(array $ids) {
  $viewer = $this->getEngine()->getConfig('viewer');

  return id(new RequirementQuery())
    ->setViewer($viewer)
    ->withIDs($ids)
    ->execute();
}
}
