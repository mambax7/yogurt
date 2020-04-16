<?php declare(strict_types=1);

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

use Xmf\Request;
use XoopsModules\Yogurt;
use XoopsModules\Yogurt\IndexController;

require __DIR__ . '/header.php';

$op = 'form';

//require_once __DIR__ . '/class/yogurt_controller.php';
$controller = new Yogurt\IndexController($xoopsDB, $xoopsUser);

/**
 * Fetching numbers of groups friends videos pictures etc...
 */
$nbSections = $controller->getNumbersSections();

    $GLOBALS['xoopsOption']['template_main'] = 'yogurt_memberslist.tpl';
    require XOOPS_ROOT_PATH . '/header.php';
    $iamadmin = $xoopsUserIsAdmin;
    $myts     = MyTextSanitizer::getInstance();
    $criteria = new CriteriaCompo();
    if (!empty($_POST['user_uname'])) {
        $match = !empty($_POST['user_uname_match']) ? Request::getInt('user_uname_match', 0, 'POST') : XOOPS_MATCH_START;
        switch ($match) {
            case XOOPS_MATCH_START:
                $criteria->add(new Criteria('uname', $myts->addSlashes(trim($_POST['user_uname'])) . '%', 'LIKE'));
                break;
            case XOOPS_MATCH_END:
                $criteria->add(new Criteria('uname', '%' . $myts->addSlashes(trim($_POST['user_uname'])), 'LIKE'));
                break;
            case XOOPS_MATCH_EQUAL:
                $criteria->add(new Criteria('uname', $myts->addSlashes(trim($_POST['user_uname']))));
                break;
            case XOOPS_MATCH_CONTAIN:
                $criteria->add(
                    new Criteria('uname', '%' . $myts->addSlashes(trim($_POST['user_uname'])) . '%', 'LIKE')
                );
                break;
        }
    }
    if (!empty($_POST['user_name'])) {
        $match = !empty($_POST['user_name_match']) ? Request::getInt('user_name_match', 0, 'POST') : XOOPS_MATCH_START;
        switch ($match) {
            case XOOPS_MATCH_START:
                $criteria->add(new Criteria('name', $myts->addSlashes(trim($_POST['user_name'])) . '%', 'LIKE'));
                break;
            case XOOPS_MATCH_END:
                $criteria->add(
                    new Criteria('name', '%' . $myts->addSlashes(trim($_POST['user_name'])) . '%', 'LIKE')
                );
                break;
            case XOOPS_MATCH_EQUAL:
                $criteria->add(new Criteria('name', $myts->addSlashes(trim($_POST['user_name']))));
                break;
            case XOOPS_MATCH_CONTAIN:
                $criteria->add(
                    new Criteria('name', '%' . $myts->addSlashes(trim($_POST['user_name'])) . '%', 'LIKE')
                );
                break;
        }
    }
    if (!empty($_POST['user_email'])) {
        $match = !empty($_POST['user_email_match']) ? Request::getInt('user_email_match', 0, 'POST') : XOOPS_MATCH_START;
        switch ($match) {
            case XOOPS_MATCH_START:
                $criteria->add(new Criteria('email', $myts->addSlashes(trim($_POST['user_email'])) . '%', 'LIKE'));
                break;
            case XOOPS_MATCH_END:
                $criteria->add(new Criteria('email', '%' . $myts->addSlashes(trim($_POST['user_email'])), 'LIKE'));
                break;
            case XOOPS_MATCH_EQUAL:
                $criteria->add(new Criteria('email', $myts->addSlashes(trim($_POST['user_email']))));
                break;
            case XOOPS_MATCH_CONTAIN:
                $criteria->add(
                    new Criteria('email', '%' . $myts->addSlashes(trim($_POST['user_email'])) . '%', 'LIKE')
                );
                break;
        }
        if (!$iamadmin) {
            $criteria->add(new Criteria('user_viewemail', 1));
        }
    }
    if (!empty($_POST['user_url'])) {
        $url = formatURL(trim($_POST['user_url']));
        $criteria->add(new Criteria('url', $myts->addSlashes($url) . '%', 'LIKE'));
    }

    if (!empty($_POST['user_from'])) {
        $criteria->add(new Criteria('user_from', '%' . $myts->addSlashes(trim($_POST['user_from'])) . '%', 'LIKE'));
    }
    if (!empty($_POST['user_intrest'])) {
        $criteria->add(
            new Criteria('user_intrest', '%' . $myts->addSlashes(trim($_POST['user_intrest'])) . '%', 'LIKE')
        );
    }
    if (!empty($_POST['user_occ'])) {
        $criteria->add(new Criteria('user_occ', '%' . $myts->addSlashes(trim($_POST['user_occ'])) . '%', 'LIKE'));
    }
    if (!empty($_POST['user_lastlog_more']) && is_numeric($_POST['user_lastlog_more'])) {
        $f_user_lastlog_more = (int)trim($_POST['user_lastlog_more']);
        $time                = time() - (60 * 60 * 24 * $f_user_lastlog_more);
        if ($time > 0) {
            $criteria->add(new Criteria('last_login', $time, '<'));
        }
    }
    if (!empty($_POST['user_lastlog_less']) && is_numeric($_POST['user_lastlog_less'])) {
        $f_user_lastlog_less = (int)trim($_POST['user_lastlog_less']);
        $time                = time() - (60 * 60 * 24 * $f_user_lastlog_less);
        if ($time > 0) {
            $criteria->add(new Criteria('last_login', $time, '>'));
        }
    }
    if (!empty($_POST['user_reg_more']) && is_numeric($_POST['user_reg_more'])) {
        $f_user_reg_more = (int)trim($_POST['user_reg_more']);
        $time            = time() - (60 * 60 * 24 * $f_user_reg_more);
        if ($time > 0) {
            $criteria->add(new Criteria('user_regdate', $time, '<'));
        }
    }
    if (!empty($_POST['user_reg_less']) && is_numeric($_POST['user_reg_less'])) {
        $f_user_reg_less = Request::getInt('user_reg_less', 0, 'POST');
        $time            = time() - (60 * 60 * 24 * $f_user_reg_less);
        if ($time > 0) {
            $criteria->add(new Criteria('user_regdate', $time, '>'));
        }
    }
    if (isset($_POST['user_posts_more']) && is_numeric($_POST['user_posts_more'])) {
        $criteria->add(new Criteria('posts', Request::getInt('user_posts_more', 0, 'POST'), '>'));
    }
    if (!empty($_POST['user_posts_less']) && is_numeric($_POST['user_posts_less'])) {
        $criteria->add(new Criteria('posts', Request::getInt('user_posts_less', 0, 'POST'), '<'));
    }
    $criteria->add(new Criteria('level', 0, '>'));
    $validsort = ['uname', 'email', 'last_login', 'user_regdate', 'posts'];
    $sort = (!in_array($xoopsModuleConfig['sortmembers'], $validsort ) ) ? 'uname' : $xoopsModuleConfig['sortmembers'];

    $order     = 'ASC';
	if ( isset($xoopsModuleConfig['membersorder']) && $xoopsModuleConfig['membersorder'] == 'DESC' ) {
        $order = 'DESC';
    }
    $limit = (!empty($xoopsModuleConfig['membersperpage'])) ? intval($xoopsModuleConfig['membersperpage']) : 20;
    if (0 === $limit || $limit > 50) {
        $limit = 50;
    }

    $start         = Request::getInt('start', 0, 'POST');
    $memberHandler = xoops_getHandler('member');
    $total         = $memberHandler->getUserCount($criteria);
	$total = $memberHandler->getUserCount($criteria);
    $xoopsTpl->assign('totalmember', $total);
	//Show last member
	$result = $GLOBALS['xoopsDB']->query('SELECT uid, uname FROM ' . $GLOBALS['xoopsDB']->prefix('users') . ' WHERE level > 0 ORDER BY uid DESC', 1, 0);
	list($latestuid, $latestuser) = $GLOBALS['xoopsDB']->fetchRow($result);
	$xoopsTpl->assign('latestmember', " <a href='" . XOOPS_URL . '/userinfo.php?uid=' . $latestuid . "'>" . $latestuser . '</a>');
	$xoopsTpl->assign('welcomemessage', $xoopsModuleConfig['welcomemessage']);
	
	
    $xoopsTpl->assign('lang_search', _MD_YOGURT_SEARCH);
    $xoopsTpl->assign('lang_results', _MD_YOGURT_RESULTS);
    if (0 === $total) {
        $xoopsTpl->assign('lang_nonefound', _MD_YOGURT_NOFOUND);
    } elseif ($start < $total) {
        $xoopsTpl->assign('lang_username', _MD_YOGURT_UNAME);
        $xoopsTpl->assign('lang_realname', _MD_YOGURT_REALNAME);
        $xoopsTpl->assign('lang_avatar', _MD_YOGURT_AVATAR);
        $xoopsTpl->assign('lang_email', _MD_YOGURT_EMAIL);
        $xoopsTpl->assign('lang_privmsg', _MD_YOGURT_PM);
        $xoopsTpl->assign('lang_regdate', _MD_YOGURT_REGDATE);
        $xoopsTpl->assign('lang_lastlogin', _MD_YOGURT_LASTLOGIN);
        $xoopsTpl->assign('lang_posts', _MD_YOGURT_POSTS);
        $xoopsTpl->assign('lang_url', _MD_YOGURT_URL);
        $xoopsTpl->assign('lang_admin', _MD_YOGURT_ADMIN);
        if ($iamadmin) {
            $xoopsTpl->assign('is_admin', true);
        }
      //  $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $foundusers = $memberHandler->getUsers($criteria, true);
        foreach (array_keys($foundusers) as $j) {
            $userdata['avatar']   = $foundusers[$j]->getVar('user_avatar');
            $userdata['realname'] = $foundusers[$j]->getVar('name');
            $userdata['name']     = $foundusers[$j]->getVar('uname');
            $userdata['id']       = $foundusers[$j]->getVar('uid');
			$userdata['uid']      = $foundusers[$j]->getVar('uid');  
	        
			$petition = 0;
			if (1 === $controller->isOwner) {
			$criteria_uidpetition = new Criteria('petitionfrom_uid', $controller->uidOwner);
			$newpetition          = $controller->petitionsFactory->getObjects($criteria_uidpetition);
			if ($newpetition) {
			$nb_petitions      = count($newpetition);
			$petitionerHandler = xoops_getHandler('member');
			$petitioner        = $petitionerHandler->getUser($newpetition[0]->getVar('petitioner_uid'));
			$petitioner_uid    = $petitioner->getVar('uid');
			$petitioner_uname  = $petitioner->getVar('uname');
			$petitioner_avatar = $petitioner->getVar('user_avatar');
			$petition_id       = $newpetition[0]->getVar('friendpet_id');
			$petition          = 1;
				}
			}
			
			$criteria_friends = new Criteria('friend1_uid', $controller->uidOwner);
			$criteria_isfriend = new CriteriaCompo(new Criteria('friend2_uid', $userdata['uid']));
            $criteria_isfriend->add($criteria_friends);
			$controller->isFriend = $controller->friendshipsFactory->getCount($criteria_isfriend);
			$userdata['isFriend'] = $controller->isFriend;  
			
			$friendpetitionFactory = new Yogurt\FriendpetitionHandler($xoopsDB);
			
			$criteria_selfrequest = new Criteria('petitioner_uid', $controller->uidOwner);
			$criteria_isselfrequest = new CriteriaCompo(new Criteria('petitionto_uid', $userdata['uid']));
            $criteria_isselfrequest->add($criteria_selfrequest);
			$controller->isSelfRequest = $friendpetitionFactory->getCount($criteria_isselfrequest);
			$userdata['selffriendrequest'] = $controller->isSelfRequest; 
			if ($controller->isSelfRequest > 0) { 
			$xoopsTpl->assign('self_uid', $controller->uidOwner); }
			$xoopsTpl->assign('lang_myfriend', _MD_YOGURT_MYFRIEND);
			$xoopsTpl->assign('lang_friendrequestsent', _MD_YOGURT_FRIENDREQUESTSENT);
     		$xoopsTpl->assign('lang_friendshipstatus', _MD_YOGURT_FRIENDSHIPSTATUS);
		
     		$criteria_otherrequest = new Criteria('petitioner_uid', $userdata['uid']);
			$criteria_isotherrequest = new CriteriaCompo(new Criteria('petitionto_uid', $controller->uidOwner));
            $criteria_isotherrequest->add($criteria_otherrequest);
			$controller->isOtherRequest = $friendpetitionFactory->getCount($criteria_isotherrequest);
			$userdata['otherfriendrequest'] = $controller->isOtherRequest; 
			if ($controller->isOtherRequest > 0) { 
			$xoopsTpl->assign('other_uid', $userdata['uid']); }
	
			
            if (1 === $foundusers[$j]->getVar('user_viewemail') || $iamadmin) {
                $userdata['email'] = "<a href='mailto:" . $foundusers[$j]->getVar(
                        'email'
                    ) . "'><img src='" . XOOPS_URL . "/images/icons/email.gif' border='0' alt='" . sprintf(
                                         _SENDEMAILTO,
                                         $foundusers[$j]->getVar('uname', 'E')
                                     ) . "'></a>";
            } else {
                $userdata['email'] = '&nbsp;';
            }
            if ($xoopsUser) {
                $userdata['pmlink'] = "<a href='javascript:openWithSelfMain(\"" . XOOPS_URL . '/pmlite.php?send2=1&amp;to_userid=' . $foundusers[$j]->getVar(
                        'uid'
                    ) . "\",\"pmlite\",450,370);'><img src='" . XOOPS_URL . "/images/icons/pm.gif' border='0' alt='" . sprintf(
                                          _SENDPMTO,
                                          $foundusers[$j]->getVar(
                                              'uname',
                                              'E'
                                          )
                                      ) . "'></a>";
            } else {
                $userdata['pmlink'] = '&nbsp;';
            }
            if ('' !== $foundusers[$j]->getVar('url', 'E')) {
                $userdata['website'] = "<a href='" . $foundusers[$j]->getVar(
                        'url',
                        'E'
                    ) . "' target='_blank'><img src='" . XOOPS_URL . "/images/icons/www.gif' border='0' alt='" . _VISITWEBSITE . "'></a>";
            } else {
                $userdata['website'] = '&nbsp;';
            }
            $userdata['registerdate'] = formatTimestamp($foundusers[$j]->getVar('user_regdate'), 's');
            if (0 !== $foundusers[$j]->getVar('last_login')) {
                $userdata['lastlogin'] = formatTimestamp($foundusers[$j]->getVar('last_login'), 'm');
            } else {
                $userdata['lastlogin'] = '&nbsp;';
            }
            $userdata['posts'] = $foundusers[$j]->getVar('posts');
            if ($iamadmin) {
                $userdata['adminlink'] = "<a href='" . XOOPS_URL . '/modules/system/admin.php?fct=users&amp;uid=' . (int)$foundusers[$j]->getVar(
                        'uid'
                    ) . "&amp;op=modifyUser'>" . _EDIT . "</a> | <a href='" . XOOPS_URL . '/modules/system/admin.php?fct=users&amp;op=delUser&amp;uid=' . (int)$foundusers[$j]->getVar(
                        'uid'
                    ) . "'>" . _DELETE . '</a>';
            }
            $xoopsTpl->append('users', $userdata);
        }
        $totalpages = ceil($total / $limit);
        if ($totalpages > 1) {
            $hiddenform = "<form name='findnext' action='searchmembers.php' method='post'>";
            foreach ($_POST as $k => $v) {
                $hiddenform .= "<input type='hidden' name='" . $myts->htmlSpecialChars($k) . "' value='" . $myts->htmlSpecialChars($v) . "'>\n";
            }
            if (!isset($_POST['limit'])) {
                $hiddenform .= "<input type='hidden' name='limit' value='" . $limit . "'>\n";
            }
            if (!isset($_POST['start'])) {
                $hiddenform .= "<input type='hidden' name='start' value='" . $start . "'>\n";
            }
            $prev = $start - $limit;
            if ($start - $limit >= 0) {
                $hiddenform .= "<a href='#0' onclick='document.findnext.start.value=" . $prev . ";document.findnext.submit();'>" . _MD_YOGURT_PREVIOUS . "</a>&nbsp;\n";
            }
            $counter     = 1;
            $currentpage = ($start + $limit) / $limit;
            while ($counter <= $totalpages) {
                if ($counter === $currentpage) {
                    $hiddenform .= '<b>' . $counter . '</b> ';
                } elseif (($counter > $currentpage - 4 && $counter < $currentpage + 4) || 1 === $counter || $counter === $totalpages) {
                    if ($counter === $totalpages && $currentpage < $totalpages - 4) {
                        $hiddenform .= '... ';
                    }
                    $hiddenform .= "<a href='#" . $counter . "' onclick='document.findnext.start.value=" . ($counter - 1) * $limit . ";document.findnext.submit();'>" . $counter . '</a> ';
                    if (1 === $counter && $currentpage > 5) {
                        $hiddenform .= '... ';
                    }
                }
                $counter++;
            }
            $next = $start + $limit;
            if ($total > $next) {
                $hiddenform .= "&nbsp;<a href='#" . $total . "' onclick='document.findnext.start.value=" . $next . ";document.findnext.submit();'>" . _MD_YOGURT_NEXT . "</a>\n";
            }
            $hiddenform .= '</form>';
            $xoopsTpl->assign('pagenav', $hiddenform);
            $xoopsTpl->assign('lang_numfound', sprintf(_MD_YOGURT_USERSFOUND, $total));
        }
    }


/**
 * Adding to the module js and css of the lightbox and new ones
 */
$xoTheme->addStylesheet(
    XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/css/yogurt.css'
);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/css/jquery.tabs.css');
// what browser they use if IE then add corrective script.
if (false !== stripos($_SERVER['HTTP_USER_AGENT'], 'msie')) {
    $xoTheme->addStylesheet(
        XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/css/jquery.tabs-ie.css'
    );
}
//$xoTheme->addStylesheet(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/css/lightbox.css');
//$xoTheme->addScript(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/js/prototype.js');
//$xoTheme->addScript(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/js/scriptaculous.js?load=effects');
//$xoTheme->addScript(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/js/lightbox.js');
//$xoTheme->addStylesheet(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/include/jquery.lightbox-0.3.css');
$xoTheme->addScript(
    XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/js/jquery.js'
);
$xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/js/jquery.lightbox-0.3.js');
$xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/js/yogurt.js');

//permissions
$xoopsTpl->assign('allow_notes', $controller->checkPrivilegeBySection('notes'));
$xoopsTpl->assign('allow_friends', $controller->checkPrivilegeBySection('friends'));
$xoopsTpl->assign('allow_groups', $controller->checkPrivilegeBySection('groups'));
$xoopsTpl->assign('allow_pictures', $controller->checkPrivilegeBySection('pictures'));
$xoopsTpl->assign('allow_videos', $controller->checkPrivilegeBySection('videos'));
$xoopsTpl->assign('allow_audios', $controller->checkPrivilegeBySection('audio'));

//xoopsToken
$xoopsTpl->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML());
//petitions to become friend
if (1 === $petition) {
    $xoopsTpl->assign('lang_youhavexpetitions', sprintf(_MD_YOGURT_YOUHAVEXPETITIONS, $nb_petitions));
    $xoopsTpl->assign('petitioner_uid', $petitioner_uid);
    $xoopsTpl->assign('petitioner_uname', $petitioner_uname);
    $xoopsTpl->assign('petitioner_avatar', $petitioner_avatar);
    $xoopsTpl->assign('petition', $petition);
    $xoopsTpl->assign('petition_id', $petition_id);
    $xoopsTpl->assign('lang_rejected', _MD_YOGURT_UNKNOWNREJECTING);
    $xoopsTpl->assign('lang_accepted', _MD_YOGURT_UNKNOWNACCEPTING);
    $xoopsTpl->assign('lang_acquaintance', _MD_YOGURT_AQUAITANCE);
    $xoopsTpl->assign('lang_friend', _MD_YOGURT_FRIEND);
    $xoopsTpl->assign('lang_bestfriend', _MD_YOGURT_BESTFRIEND);
    $linkedpetioner = '<a href="index.php?uid=' . $petitioner_uid . '">' . $petitioner_uname . '</a>';
    $xoopsTpl->assign('lang_askingfriend', sprintf(_MD_YOGURT_ASKINGFRIEND, $linkedpetioner));
}
$xoopsTpl->assign('lang_askusertobefriend', _MD_YOGURT_ASKBEFRIEND);
$xoopsTpl->assign('lang_addfriend', _MD_YOGURT_ADDFRIEND);
$xoopsTpl->assign('lang_friendshippending', _MD_YOGURT_FRIENDREQUESTPENDING);

$memberHandler = xoops_getHandler('member');
$thisUser      = $memberHandler->getUser($controller->uidOwner);
$myts          = MyTextSanitizer::getInstance();

//Owner data
$xoopsTpl->assign('uid_owner', $controller->uidOwner);
$xoopsTpl->assign('owner_uname', $controller->nameOwner);
$xoopsTpl->assign('isOwner', $controller->isOwner);
$xoopsTpl->assign('isAnonym', $controller->isAnonym);

//numbers
$xoopsTpl->assign('nb_groups', $nbSections['nbGroups']);
$xoopsTpl->assign('nb_photos', $nbSections['nbPhotos']);
$xoopsTpl->assign('nb_videos', $nbSections['nbVideos']);
$xoopsTpl->assign('nb_notes', $nbSections['nbNotes']);
$xoopsTpl->assign('nb_friends', $nbSections['nbFriends']);
$xoopsTpl->assign('nb_audio', $nbSections['nbAudio']);

//navbar
$xoopsTpl->assign('module_name', $xoopsModule->getVar('name'));
$xoopsTpl->assign(
    'lang_mysection',
    _MD_YOGURT_SEARCH . ' ' . sprintf(_MD_YOGURT_TOTALUSERS, '<span style="color:#ff0000;">' . $total . '</span>')
);
$xoopsTpl->assign('section_name', _MD_YOGURT_SEARCH);
$xoopsTpl->assign('lang_home', _MD_YOGURT_HOME);
$xoopsTpl->assign('lang_photos', _MD_YOGURT_PHOTOS);
$xoopsTpl->assign('lang_friends', _MD_YOGURT_FRIENDS);
$xoopsTpl->assign('lang_audio', _MD_YOGURT_AUDIOS);
$xoopsTpl->assign('lang_videos', _MD_YOGURT_VIDEOS);
$xoopsTpl->assign('lang_notebook', _MD_YOGURT_NOTEBOOK);
$xoopsTpl->assign('lang_profile', _MD_YOGURT_PROFILE);
$xoopsTpl->assign('lang_groups', _MD_YOGURT_GROUPS);
$xoopsTpl->assign('lang_configs', _MD_YOGURT_CONFIGSTITLE);

require __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';