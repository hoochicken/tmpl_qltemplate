<?php
/**
 * @package     qltemplate
 * @copyright   (C) 2023 ql.de
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Qltemplate\php\Qltemplate;

require_once __DIR__ . '/php/Qltemplate.php';
$this->setGenerator(null);
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
$app = Factory::getApplication();
$input = $app->getInput();
$wa    = $this->getWebAssetManager();
$this->setGenerator(null);
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

$qltemplate = new Qltemplate($this, $app, Factory::getApplication()->getIdentity(), $wa, $app->getTemplate(true)->params);
$qltemplate->addStylesheets();
$wa->addInlineStyle($qltemplate->getTemplateStyles());
?>
<!DOCTYPE html>
<html lang="<?= $qltemplate->getLanguage() ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
</head>
<body id="page" class="component page <?= $qltemplate->getBodyClass() ?>">
<jdoc:include type="component" />
<jdoc:include type="scripts" />
</body>
</html>
