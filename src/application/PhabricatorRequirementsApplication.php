<?php

final class PhabricatorRequirementsApplication extends PhabricatorApplication {

  public function getName() {
    return pht('Requirements');
  }

  public function handleRequest(AphrontRequest $request) {}
  
  public function getShortDescription() {
    return pht('Requirement management');
  }

  public function getBaseURI() {
    return '/requirements/';
  }

  public function getRoutes() {
    
    $a = new ManiphestTaskDetailController();

    return array(
        '/requirements/' => 'RequirementsOverviewController',
        '/requirements/overview/' => 'RequirementsOverviewController',
        '/requirements/R(?P<id>[1-9]\d*)' => 'RequirementsDetailsController',
        '/R(?P<id>[1-9]\d*)' => 'RequirementsDetailsController',
    );
  }


}

