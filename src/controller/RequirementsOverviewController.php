<?php

final class RequirementsOverviewController extends PhabricatorController {

    private $view;

    public function buildApplicationMenu() {
        return $this->buildSideNavView()->getMenu();
    }

    public function buildSideNavView() {
        $viewer = $this->getViewer();

        $nav = new AphrontSideNavFilterView();
        $nav->setBaseURI(new PhutilURI($this->getApplicationURI()));

        return $nav;
    }

    protected function buildApplicationCrumbs() {
        $crumbs = parent::buildApplicationCrumbs();
        return $crumbs;
    }

    public function handleRequest(AphrontRequest $request) {

        $nav = new AphrontSideNavFilterView();
        $nav->setBaseURI(new PhutilURI('/requirements/'));
        $nav->addLabel(pht('Requirements'));
        $nav->addFilter('overview', pht('Overview'));
        
        $this->view = $nav->selectFilter($this->view, 'overview');


        $crumbs = $this->buildApplicationCrumbs()
            ->addTextCrumb(pht('Overview'));

        $title = pht('Requirements overview');

        return $this->newPage()
            ->setTitle($title)
            ->setCrumbs($crumbs)
            ->setNavigation($nav);

    }
}