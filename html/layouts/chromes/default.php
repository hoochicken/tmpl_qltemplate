<?php
/**
 * @package     tpl_qlproto
 * @copyright   Copyright (C) 2016 Mareike Riegel
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

require_once __DIR__ . '/../../../php/QltemplateModules.php';

/* @var array $displayData */
/* @var stdClass $module */
/* @var Joomla\Registry\ $registry */
/* @var array $attribs */

$module = $displayData['module'] ?? new stdClass();
$params = $displayData['params'] ?? null;
$attribs = $displayData['attribs'] ?? [];
$sfx = (string)$params->get('moduleclass_sfx', '');

if (empty($module->content)) return;

$qlmodules = new Qltemplate\php\QltemplateModules($module, $params, $attribs);
$module->content = $qlmodules->getContent($module, $params, $attribs, $sfx);
echo $module->content;
