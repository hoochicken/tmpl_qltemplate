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
<body id="page" class="page <?= $qltemplate->getBodyClass() ?>">
<?php if ($this->countModules('body')) : ?>
    <jdoc:include type="modules" name="body" style="default" />
<?php endif; ?>
<?php if ($this->countModules('topbar')) : ?>
    <div class="container-topbar">
        <jdoc:include type="modules" name="topbar" style="default"/>
    </div>
<?php endif; ?>

<div class="top-bar"></div>

<div class="uk-sticky-placeholder uk-hidden-small uk-hidden-touch">
    <div data-uk-smooth-scroll data-uk-sticky="{top:-500}"><a class="tm-totop-scroller uk-animation-slide-bottom" href="#" ></a></div>
</div>

<div class="wrapper grid-block">

    <header id="header">
        <div id="logo" class="grid-block">
            <?php if ($this->countModules('logo')) : ?>
                <jdoc:include type="modules" name="logo" style="default" />
            <?php endif; ?>
        </div>

        <div id="header-menu" class="grid-block" data-uk-sticky>
            <?php if ($this->countModules('header-menu')) : ?>
                <jdoc:include type="modules" name="header-menu" style="default" />
            <?php endif; ?>
        </div>

        <?php if ($this->countModules('header-minibar')) : ?>
            <div id="header-minibar" class="grid-block">
                <jdoc:include type="modules" name="header-minibar" style="default" />
            </div>
        <?php endif; ?>
    </header>

    <?php if ($this->countModules('slider')) : ?>
        <div id="slide-zone">
            <jdoc:include type="modules" name="slider" style="default" />
        </div>
    <?php endif; ?>
</div>
<!-- end header block -->

<!-- sheet layout option -->
<div class="wrapper grid-block">
    <div id="sheet">
        <div class="sheet-body">
            <div class="wrapper grid-block content-texture ">
                <div id="main" class="grid-block row">
                    <div id="maininner" class="grid-box col col-md-<?= $qltemplate->getWidthContent() ?> col-sm-<?= $qltemplate->getWidthAll() ?>">
                        <section id="content" class="grid-block">
                            <?php if ($this->countModules('content-top')) : ?>
                                <div class="content-top">
                                    <jdoc:include type="modules" name="content-top" style="default"/>
                                </div>
                            <?php endif; ?>

                            <jdoc:include type="message" />

                            <div id="system">
                                <jdoc:include type="component" />
                            </div>

                            <?php if ($this->countModules('content-bottom-1')) : ?>
                                <section id="content-bottom-1" class="grid-block">
                                    <div class="content-bottom">
                                        <jdoc:include type="modules" name="content-bottom-1" style="default"/>
                                    </div>
                                </section>
                            <?php endif; ?>

                            <?php if ($this->countModules('content-bottom-2')) : ?>
                                <section id="content-bottom-2" class="grid-block">
                                    <div class="content-bottom">
                                        <jdoc:include type="modules" name="content-bottom-2" style="default"/>
                                    </div>
                                </section>
                            <?php endif; ?>
                        </section>
                    </div>
                    <!-- maininner end -->

                    <?php if ($this->countModules('sidebar-a')) : ?>
                        <aside id="sidebar-a" class="grid-box col-md-<?= $qltemplate->getWidthSidebarA() ?> col-sm-<?= $qltemplate->getWidthAll() ?>">
                            <div class="inner-box mb-3">
                                <jdoc:include type="modules" name="sidebar-a" style="default"/>
                            </div>
                        </aside>
                    <?php endif; ?>
                    <?php if ($this->countModules('sidebar-b')) : ?>
                        <aside id="sidebar-b" class="grid-box col-md-<?= $qltemplate->getWidthSidebarB() ?> col-sm-<?= $qltemplate->getWidthAll() ?>">
                            <div class="inner-box">
                                <jdoc:include type="modules" name="sidebar-b" style="default"/>
                            </div>
                        </aside>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($this->countModules('bottom-a')) : ?>
                <section id="bottom-a" class="mb-2">
                    <div class="inner-box">
                        <jdoc:include type="modules" name="bottom-a" style="default"/>
                    </div>
                </section>
            <?php endif; ?>
            <?php if ($this->countModules('bottom-b')) : ?>
                <section id="bottom-b" class="mb-2">
                    <div class="inner-box row">
                        <jdoc:include type="modules" name="bottom-b" style="default"/>
                    </div>
                </section>
            <?php endif; ?>
            <?php if ($this->countModules('bottom-c')) : ?>
                <section id="bottom-c" class="mb-2">
                    <div class="inner-box row">
                        <jdoc:include type="modules" name="bottom-c" style="default"/>
                    </div>
                </section>
            <?php endif; ?>
            <?php if ($this->countModules('bottom-d')) : ?>
                <section id="bottom-d" class="mb-2">
                    <div class="inner-box row">
                        <jdoc:include type="modules" name="bottom-d" style="default"/>
                    </div>
                </section>
            <?php endif; ?>
        </div>
        <!-- main end -->

        <div id="footer-block" class="">
            <div class="wrapper grid-block">
                <?php if ($this->countModules('copyright')) : ?>
                    <section id="copyright">
                        <div class="inner-box">
                            <jdoc:include type="modules" name="copyright" style="default"/>
                        </div>
                    </section>
                <?php endif; ?>
                <?php if ($this->countModules('footer')) : ?>
                    <section id="copyright">
                        <div class="inner-box">
                            <jdoc:include type="modules" name="footer" style="default"/>
                        </div>
                    </section>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
<?php if ($this->countModules('debug')) : ?>
    <div class="debug inner-box">
        <jdoc:include type="modules" name="debug" style="default"/>
    </div>
<?php endif; ?>
</body>

<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>
</html>
