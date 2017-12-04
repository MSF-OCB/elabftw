<?php
/**
 * profile.php
 *
 * @author Nicolas CARPi <nicolas.carpi@curie.fr>
 * @copyright 2012 Nicolas CARPi
 * @see https://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */
namespace Elabftw\Elabftw;

use Exception;

/**
 * Display profile of current user
 *
 */
require_once 'app/init.inc.php';
$App->pageTitle = _('Profile');

try {
    $userId = $Request->query->get('id');
    if (!$userId) { $userId = $Users->userid; }
    $ProfileUsers = new Users($userId, $Auth, $Config);

    // get total number of experiments
    $Entity = new Experiments($Users);
    $Entity->setUseridFilter($ProfileUsers->userid);
    $itemsArr = $Entity->read();
    $count = count($itemsArr);

    $UserStats = new UserStats($ProfileUsers, $count);
    $TagCloud = new TagCloud($ProfileUsers->userid);
    // TEAM GROUPS
    $TeamGroups = new TeamGroups($ProfileUsers);

    $template = 'profile.html';
    $renderArr = array(
        'ProfileUsers' => $ProfileUsers,
        'UserStats' => $UserStats,
        'TagCloud' => $TagCloud,
        'TeamGroups' => $TeamGroups,
        'Entity' => $Entity,
        'experimentsArr' => $itemsArr,
        'experimentsCount' => $count,
    );

} catch (Exception $e) {
    $App->Logs->create('Error', $Session->get('userid'), $e->getMessage());
    $template = 'error.html';
    $renderArr = array('error' => $e->getMessage());
}

echo $App->render($template, $renderArr);
