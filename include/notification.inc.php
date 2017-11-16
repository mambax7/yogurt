<?php
// $Id: notification.inc.php,v 1.4 2008/01/22 19:57:17 marcellobrandao Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
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

/**
 * Protection against inclusion outside the site
 */
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

/**
 * @param $category
 * @param $item_id
 * @return mixed
 */
function yogurt_iteminfo($category, $item_id)
{
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname('yogurt');

    if ('global' == $category) {
        $item['name'] = '';
        $item['url']  = '';
        return $item;
    }

    global $xoopsDB;

    if ('picture' == $category) {
        $sql          = 'SELECT title,uid_owner,url FROM ' . $xoopsDB->prefix('yogurt_images') . ' WHERE uid_owner = ' . $item_id . ' LIMIT 1';
        $result       = $xoopsDB->query($sql);
        $result_array = $xoopsDB->fetchArray($result);
        /**
         * Let's get the user name of the owner of the album
         */
        $owner        = new XoopsUser();
        $identifier   = $owner->getUnameFromId($result_array['uid_owner']);
        $item['name'] = $identifier . "'s Album";
        $item['url']  = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/album.php?uid=' . $result_array['uid_owner'];
        return $item;
    }

    if ('video' == $category) {
        $sql          = 'SELECT video_id,uid_owner,video_desc,youtube_code, mainvideo FROM ' . $xoopsDB->prefix('yogurt_images') . ' WHERE uid_owner = ' . $item_id . ' LIMIT 1';
        $result       = $xoopsDB->query($sql);
        $result_array = $xoopsDB->fetchArray($result);
        /**
         * Let's get the user name of the owner of the album
         */
        $owner        = new XoopsUser();
        $identifier   = $owner->getUnameFromId($result_array['uid_owner']);
        $item['name'] = $identifier . "'s Videos";
        $item['url']  = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/seutubo.php?uid=' . $result_array['uid_owner'];
        return $item;
    }

    if ('Note' == $category) {
        $sql          = 'SELECT Note_id, Note_from, Note_to, Note_text FROM ' . $xoopsDB->prefix('yogurt_Notes') . ' WHERE Note_from = ' . $item_id . ' LIMIT 1';
        $result       = $xoopsDB->query($sql);
        $result_array = $xoopsDB->fetchArray($result);
        /**
         * Let's get the user name of the owner of the album
         */
        $owner        = new XoopsUser();
        $identifier   = $owner->getUnameFromId($result_array['Note_from']);
        $item['name'] = $identifier . "'s Notes";
        $item['url']  = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/notebook.php?uid=' . $result_array['Note_from'];
        return $item;
    }
}