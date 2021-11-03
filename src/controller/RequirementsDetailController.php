<?php

final class RequirementsDetailsController extends PhabricatorController
{

    private $view;

    public function buildApplicationMenu()
    {
        return $this->buildSideNavView()->getMenu();
    }

    public function buildSideNavView()
    {
        $viewer = $this->getViewer();

        $nav = new AphrontSideNavFilterView();
        $nav->setBaseURI(new PhutilURI($this->getApplicationURI()));

        return $nav;
    }

    protected function buildApplicationCrumbs()
    {
        $crumbs = parent::buildApplicationCrumbs();
        return $crumbs;
    }

    public function handleRequest(AphrontRequest $request)
    {

        $id = $request->getURIData('id', "-10");
        $viewer = $request->getUser();
        
        $task = id(new RequirementQuery())
            ->setViewer($viewer)
            ->withIDs(array($id))
            ->executeOne();
        if (!$task) {
            $title = pht('Requirement ') . $id . pht(' does not exist');
        }else{
            $title = pht('Requirement details for ') . $id;

        }

        $nav = new AphrontSideNavFilterView();
        $nav->setBaseURI(new PhutilURI('/requirements/'));
        $nav->addLabel(pht('Requirements'));
        $nav->addFilter('overview', pht('Overview'));

        $this->view = $nav->selectFilter($this->view, 'overview');

        $crumbs = $this->buildApplicationCrumbs()
            ->addTextCrumb(pht('Details'));

        return $this->newPage()
            ->setTitle($title)
            ->setCrumbs($crumbs)
            ->setNavigation($nav);
    }
}
