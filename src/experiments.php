<?php
/**
 * experiments.php
 *
 * @author Nicolas CARPi <nicolas.carpi@curie.fr>
 * @copyright 2012 Nicolas CARPi
 * @see https://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */

namespace Elabftw\Elabftw;

use Exception;
use InvalidArgumentException;

/**
 * Entry point for all experiment stuff
 *
 */
require_once 'app/init.inc.php';
$App->pageTitle = ngettext('Experiment', 'Experiments', 2);

try {
    $Entity = new Experiments($Users);
    $EntityView = new ExperimentsView($Entity);
    $Status = new Status($Entity->Users);

    // VIEW
    if ($Request->query->get('mode') === 'view') {
        $Entity->setId($Request->query->get('id'));
        $Entity->canOrExplode('read');

        // LINKS
        $linksArr = $Entity->Links->readAll();

        // COMMENTS
        # @DEACTIVATED $commentsArr = $Entity->Comments->readAll();

        // UPLOADS
        $UploadsView = new UploadsView($Entity->Uploads);

        $template = 'view.html';

        $renderArr = array(
            'Ev' => $EntityView,
            'Entity' => $Entity,
            'Uv' => $UploadsView,
            'linksArr' => $linksArr,
            # @DEACTIVATED 'commentsArr' => $commentsArr,
            'cleanTitle' => Tools::getCleanTitle($Entity->entityData['title']),
            'mode' => 'view'
        );

    // EDIT
    } elseif ($Request->query->get('mode') === 'edit') {
        $Entity->setId($Request->query->get('id'));
        // check permissions
        $Entity->canOrExplode('write');
        // a locked experiment cannot be edited
        if ($Entity->entityData['locked']) {
            throw new Exception(_('<strong>This item is locked.</strong> You cannot edit it.'));
        }

        // REVISIONS
        $Revisions = new Revisions($Entity);

        // UPLOADS
        $UploadsView = new UploadsView($Entity->Uploads);

        // TEAM GROUPS
        $TeamGroups = new TeamGroups($Entity->Users);

        // LINKS
        $linksArr = $Entity->Links->readAll();

        // STEPS
        $stepsArr = $Entity->Steps->readAll();

        $template = 'edit.html';

        $renderArr = array(
            'Entity' => $Entity,
            'Uv' => $UploadsView,
            'mode' => 'edit',
            'new' => $Request->query->get('new'),
            'Revisions' => $Revisions,
            'Categories' => $Status,
            'TeamGroups' => $TeamGroups,
            'linksArr' => $linksArr,
            'stepsArr' => $stepsArr,
            'cleanTitle' => Tools::getCleanTitle($Entity->entityData['title']),
            'maxUploadSize' => Tools::returnMaxUploadSize(),
            'lang' => Tools::getCalendarLang($App->Users->userData['lang'])
        );

    // DEFAULT MODE IS SHOW
    } else {
        $searchType = '';
        $tag = '';
        $query = '';

        // CATEGORY FILTER
        if (Tools::checkId($Request->query->get('cat'))) {
            $Entity->categoryFilter = " AND status.id = " . $Request->query->get('cat');
            $searchType = 'filter';
        }
        // TEAM GROUPS FILTER
        $TeamGroups = new TeamGroups($Entity->Users);
        if (Tools::checkId($Request->query->get('grp'))) {
            $Entity->teamgroupFilter = " AND team_groups.id = " . $Request->query->get('grp');
            $searchType = 'grp';
        }

        // TAG FILTER
        if ($Request->query->get('tag') != '') {
            $tag = filter_var($Request->query->get('tag'), FILTER_SANITIZE_STRING);
            $Entity->tagFilter = " AND tagt.tag LIKE '" . $tag . "'";
            $searchType = 'tag';
        }
        // QUERY FILTER
        if ($Request->query->get('q') != '') {
            $query = filter_var($Request->query->get('q'), FILTER_SANITIZE_STRING);
            $Entity->queryFilter = " AND (
                title LIKE '%$query%' OR
                date LIKE '%$query%' OR
                body LIKE '%$query%' OR
                elabid LIKE '%$query%'
            )";
            $searchType = 'query';
        }
        // ORDER
        $order = 'team_group'; //@TODO: set the value by default in SQL

        // load the pref from the user
        if (isset($Entity->Users->userData['orderby'])) {
            $order = $Entity->Users->userData['orderby'];
        }

        // now get pref from the filter-order-sort menu
        if ($Request->query->has('order')) {
            $order = $Request->query->get('order');
        }

        if ($order === 'cat') {
            $Entity->order = 'status.ordering'; //'experiments.team_group';
        } elseif ($order === 'date' || $order === 'rating' || $order === 'title' || $order === 'team_group') {
            $Entity->order = 'experiments.' . $order;
        # @DEACTIVATED } elseif ($order === 'comment') {
        # @DEACTIVATED     $Entity->order = 'experiments_comments.recent_comment';
        }

        // SORT
        $sort = 'asc'; //@TODO: set the value by default in SQL

        // load the pref from the user
        if (isset($Entity->Users->userData['sort'])) {
            $sort = $Entity->Users->userData['sort'];
        }

        // now get pref from the filter-order-sort menu
        if ($Request->query->has('sort')) {
            $sort = $Request->query->get('sort');
        }

        if (in_array(strtolower($sort), array('asc', 'desc'))) {
            $Entity->sort = $sort;
        }

        $Status = new Status($Entity->Users);
        $categoryArr = $Status->readAll();

        $Templates = new Templates($Entity->Users);
        $templatesArr = $Templates->readFromUserid();

        // READ ALL ITEMS

        // related filter
        if (Tools::checkId($Request->query->get('related'))) {
            $searchType = 'related';
            $itemsArr = $Entity->readRelated($Request->query->get('related'));
        } else {
            // filter by user only if we are not making a search
            if (!$Users->userData['show_team'] && ($searchType === '' || $searchType === 'filter')) {
                $Entity->setUseridFilter();
            }

            $itemsArr = $Entity->read();
        }

        $template = 'show.html';
        $renderArr = array(
            'Entity' => $Entity,
            'itemsArr' => $itemsArr,
            'searchType' => $searchType,
            'categoryArr' => $categoryArr,
            'templatesArr' => $templatesArr,
            'TeamGroups' => $TeamGroups,
            'tag' => $tag,
            'query' => $query,
            'order' => $order,
            'sort' => $sort,
        );
    }
} catch (InvalidArgumentException $e) {
    $template = 'error.html';
    $renderArr = array('error' => $e->getMessage());
} catch (Exception $e) {
    $debug = false;
    $message = $e->getMessage();
    if ($debug) {
        $message .= ' ' . $e->getFile() . '(' . $e->getLine() . ')';
    }
    $template = 'error.html';
    $renderArr = array('error' => $message);
}

echo $App->render($template, $renderArr);
