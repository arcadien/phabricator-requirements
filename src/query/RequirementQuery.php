<?php

final class RequirementQuery extends PhabricatorCursorPagedPolicyAwareQuery
{

    private $requirementIDs;
    private $requirementPHIDs;
    private $authorPHIDs;
    private $ownerPHIDs;
    private $hasOpenSubrequirements;

    const STATUS_ANY      = 'status-any';
    const COVERED_NONE    = 'covered-none';
    const COVERED_PARTIAL = 'covered-partial';
    const COVERED_ALL     = 'covered-all';
    private $status       = self::STATUS_ANY;

    private $statuses;
    private $priorities;
    private $subpriorities;


    private $groupBy          = 'group-none';
    const GROUP_NONE          = 'group-none';
    const GROUP_PRIORITY      = 'group-priority';
    const GROUP_OWNER         = 'group-owner';
    const GROUP_STATUS        = 'group-status';
    const GROUP_PROJECT       = 'group-project';

    const ORDER_PRIORITY      = 'order-priority';
    const ORDER_CREATED       = 'order-created';
    const ORDER_MODIFIED      = 'order-modified';
    const ORDER_TITLE         = 'order-title';

    public function getQueryApplicationClass()
    {
        return "PhabricatorRequirementsApplication";
    }

    public function withAuthors(array $authors)
    {
        $this->authorPHIDs = $authors;
        return $this;
    }

    public function withIDs(array $ids)
    {
        $this->requirementIDs = $ids;
        return $this;
    }

    public function withPHIDs(array $phids)
    {
        $this->requirementPHIDs = $phids;
        return $this;
    }

    public function withOwners(array $owners)
    {
        if ($owners === array()) {
            throw new Exception(pht('Empty withOwners() constraint is not valid.'));
        }

        $no_owner = PhabricatorPeopleNoOwnerDatasource::FUNCTION_TOKEN;
        $any_owner = PhabricatorPeopleAnyOwnerDatasource::FUNCTION_TOKEN;

        foreach ($owners as $k => $phid) {
            if ($phid === $no_owner || $phid === null) {
                $this->noOwner = true;
                unset($owners[$k]);
                break;
            }
            if ($phid === $any_owner) {
                $this->anyOwner = true;
                unset($owners[$k]);
                break;
            }
        }

        if ($owners) {
            $this->ownerPHIDs = $owners;
        }

        return $this;
    }

    public function withStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function withStatuses(array $statuses)
    {
        $this->statuses = $statuses;
        return $this;
    }

    public function withPriorities(array $priorities)
    {
        $this->priorities = $priorities;
        return $this;
    }

    public function withSubpriorities(array $subpriorities)
    {
        $this->subpriorities = $subpriorities;
        return $this;
    }

    public function withSubscribers(array $subscribers)
    {
        $this->subscriberPHIDs = $subscribers;
        return $this;
    }

    public function setGroupBy($group)
    {
        $this->groupBy = $group;

        switch ($this->groupBy) {
            case self::GROUP_NONE:
                $vector = array();
                break;
            case self::GROUP_PRIORITY:
                $vector = array('priority');
                break;
            case self::GROUP_OWNER:
                $vector = array('owner');
                break;
            case self::GROUP_STATUS:
                $vector = array('status');
                break;
            case self::GROUP_PROJECT:
                $vector = array('project');
                break;
        }

        $this->setGroupVector($vector);

        return $this;
    }

    public function withOpenSubrequirements($value)
    {
        $this->hasOpenSubrequirements = $value;
        return $this;
    }

    public function withOpenParents($value)
    {
        $this->hasOpenParents = $value;
        return $this;
    }

    public function withParentTaskIDs(array $ids)
    {
        $this->parentTaskIDs = $ids;
        return $this;
    }

    public function withSubtaskIDs(array $ids)
    {
        $this->subtaskIDs = $ids;
        return $this;
    }

    public function withDateCreatedBefore($date_created_before)
    {
        $this->dateCreatedBefore = $date_created_before;
        return $this;
    }

    public function withDateCreatedAfter($date_created_after)
    {
        $this->dateCreatedAfter = $date_created_after;
        return $this;
    }

    public function withDateModifiedBefore($date_modified_before)
    {
        $this->dateModifiedBefore = $date_modified_before;
        return $this;
    }

    public function withDateModifiedAfter($date_modified_after)
    {
        $this->dateModifiedAfter = $date_modified_after;
        return $this;
    }

    public function withClosedEpochBetween($min, $max)
    {
        $this->closedEpochMin = $min;
        $this->closedEpochMax = $max;
        return $this;
    }

    public function withCloserPHIDs(array $phids)
    {
        $this->closerPHIDs = $phids;
        return $this;
    }

    public function needSubscriberPHIDs($bool)
    {
        $this->needSubscriberPHIDs = $bool;
        return $this;
    }

    public function needProjectPHIDs($bool)
    {
        $this->needProjectPHIDs = $bool;
        return $this;
    }

    public function withBridgedObjectPHIDs(array $phids)
    {
        $this->bridgedObjectPHIDs = $phids;
        return $this;
    }

    public function withSubtypes(array $subtypes)
    {
        $this->subtypes = $subtypes;
        return $this;
    }

    public function withColumnPHIDs(array $column_phids)
    {
        $this->columnPHIDs = $column_phids;
        return $this;
    }

    public function withSpecificGroupByProjectPHID($project_phid)
    {
        $this->specificGroupByProjectPHID = $project_phid;
        return $this;
    }

    public function newResultObject()
    {
        return new Requirement();
    }

    protected function loadPage()
    {
        $requirements_dao = new RequirementDAO();
        $conn = $requirements_dao->establishConnection('r');

        $where = $this->buildWhereClause($conn);

        $group_column = qsprintf($conn, '');
        switch ($this->groupBy) {
            case self::GROUP_PROJECT:
                $group_column = qsprintf(
                    $conn,
                    ', projectGroupName.indexedObjectPHID projectGroupPHID'
                );
                break;
        }

        // $rows = queryfx_all(
        //   $conn,
        //   '%Q %Q FROM %T requirements %Q %Q %Q %Q %Q %Q',
        //   $this->buildSelectClause( $conn ),
        //   $group_column,
        //   $task_dao->getTableName(),
        //   $this->buildJoinClause( $conn ),
        //   $where,
        //   $this->buildGroupClause( $conn ),
        //   $this->buildHavingClause( $conn ),
        //   $this->buildOrderClause( $conn ),
        //   $this->buildLimitClause( $conn ) );

        $rows = queryfx_all(
            $conn,
            '%Q %Q FROM %T requirements',
            $this->buildSelectClause($conn),
            $group_column,
            $requirements_dao->getTableName()
        );

        switch ($this->groupBy) {
            case self::GROUP_PROJECT:
                $data = ipull($rows, null, 'id');
                break;
            default:
                $data = $rows;
                break;
        }

        $data = $this->didLoadRawRows($data);
        $requirements = $requirements_dao->loadAllFromArray($data);

        switch ($this->groupBy) {
            case self::GROUP_PROJECT:
                $results = array();
                foreach ($rows as $row) {
                    $task = clone $requirements[$row['id']];
                    $task->attachGroupByProjectPHID($row['projectGroupPHID']);
                    $results[] = $task;
                }
                $requirements = $results;
                break;
        }

        return $requirements;
    }
}
