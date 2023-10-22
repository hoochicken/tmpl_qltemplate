<?php
/**
 * @package     qltemplate
 * @copyright   (C) 2023 ql.de
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Qltemplate\php\Qltemplate;

require_once __DIR__ . '/php/Qltemplate.php';
$errorPage = Qltemplate::getErrorLink($this);
header('Location: ' . $errorPage);
exit;