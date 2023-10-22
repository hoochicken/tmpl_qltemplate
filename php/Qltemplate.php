<?php

namespace Qltemplate\php;

use JBrowser;
use JFactory;
use JLoader;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\ErrorDocument;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;
use Joomla\CMS\User\User;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use JSite;

class Qltemplate
{
    private const TEMPLATE_COLOR = '#30a6dc';
    private const TEMPLATE_CONTERPART = '#ffffff';
    private const FONT_COLOR = '#00000';
    private const BODY_BG = '#e0f3ff';
    private const TEMPLATE_ACTIVE = '#ffbf00';
    private const PAGE_SECTION_DEFAULT = [3, 6, 3];
    private const PAGE_SECTION_SIDEBAR_A = 0;
    private const PAGE_SECTION_CONTENT = 1;
    private const PAGE_SECTION_SIDEBAR_B = 2;
    private HtmlDocument $template;
    private string $templateName;
    private CMSApplicationInterface $app;
    private Input $input;
    private Document $document;
    private Registry $params;
    private bool $logged = false;
    private string $language = 'en-GB';
    private string $direction = 'ltr';
    private string $classBody = '';
    private int $widthAll = 12;
    private int $widthContent = self::PAGE_SECTION_DEFAULT[self::PAGE_SECTION_CONTENT];
    private int $widthSidebarA = self::PAGE_SECTION_DEFAULT[self::PAGE_SECTION_SIDEBAR_A];
    private int $widthSidebarB = self::PAGE_SECTION_DEFAULT[self::PAGE_SECTION_SIDEBAR_B];
    private string $templatePath = '';
    private string $templateStyle = '';
    private bool $mobile = false;
    private bool $authorized = false;
    private string $device = '';
    private string $browser = '';
    private array $pageSection = self::PAGE_SECTION_DEFAULT;
    private string $pageclass = '';
    private ?User $user = null;
    private WebAssetManager $wa;

    public function __construct(HtmlDocument $template, CMSApplicationInterface $app, ?User $user, WebAssetManager $wa, $params)
    {
        $this->app = $app;
        $this->template = $template;
        $this->templateName = $template->template;
        $this->input = $app->input;
        $this->document = $app->getDocument();
        $this->user = $user;
        $this->wa = $wa;

        $this->checkMobile();
        $this->params = $params;
        $this->logged = (0 < (int)$app->getUserState('id'));
        $this->language = $this->document->language;
        $this->direction = $this->document->direction;
        $this->authorized = ($this->user->authorise('core.edit', 'com_content'));
        $this->initPageSections();
        $this->initTemplateStyle();
        $this->initBodyClass();
        $this->templatePath = $this->document->getBase() . 'templates/' . $this->templateName . '/';
    }

    public function addStylesheets()
    {
        // font awsome
        $this->wa->registerAndUseStyle('qltemplate-font-awesome', 'joomla-fontawesome.min.css');

        // bootstrap
        $this->wa->registerAndUseStyle('qltemplate-bootstrap', $this->templatePath . 'bootstrap5/bootstrap.css');
        $this->wa->registerAndUseScript('qltemplate-bootstrap', $this->templatePath . 'bootstrap5/bootstrap.js');

        // layout default + custom styles
        $this->wa->registerAndUseStyle('qltemplate-layout', $this->templatePath . 'css/layout.css');
        $this->wa->registerAndUseScript('qltemplate-layout', $this->templatePath . 'js/layout.js');
        $this->wa->registerAndUseStyle('qltemplate-custom', $this->templatePath . 'css/custom.css');
    }

    private function initTemplateStyle()
    {
        $siteWidth = (string)$this->params->get('siteWidth', '1050px');
        $siteWidth = ($siteWidth === '100percent') ? '100%' : ($siteWidth . 'px');

        $defaultColor = $this->params->get('templateColor', static::TEMPLATE_COLOR);
        $defaultInverse = $this->params->get('templateColorInverse', static::TEMPLATE_CONTERPART);
        $templateBackground = $this->params->get('templateBg', static::TEMPLATE_CONTERPART);
        $activeBackground = $this->params->get('', static::TEMPLATE_ACTIVE);
        $fontColor = $this->params->get('fontColor', static::FONT_COLOR);
        $headlineFontColor = $this->params->get('fontColorHeadline', static::TEMPLATE_COLOR);

        $bodyBackground = $this->params->get('bodyBg', static::BODY_BG);
        $bodyPic = $this->params->get('bodyPic', static::TEMPLATE_CONTERPART);
        if (!empty($bodyPic)) {
            $bodyPicPosition = $this->params->get('bodyPicPosition', 'center top');
            $bodyPicRepeat = $this->params->get('bodyPicRepeat', 'repeat');
            $bodyPicAttachement = $this->params->get('bodyPicAttachment', 'fixed');
            $bodySize = $this->params->get('bodyPicSize', 'auto');
            $bodyBackground .= sprintf(';background-image:url(%s);background-repeat:%s;background-attachment:%s;background-size:%s;background-position:%s', $bodyPic, $bodyPicRepeat, $bodyPicAttachement, $bodySize, $bodyPicPosition);
        }

        $style = [];
        $style[] = sprintf('body {color:%s;background:%s;font-size:%s;line-height:%s;}', $fontColor, $bodyBackground, $this->params->get('fontSize', '20') . 'px', $this->params->get('lineheight', '1.3') .'em');
        $style[] = sprintf('#header-menu {background:%s;}', $defaultColor);
        $style[] = sprintf('#content, #header-minibar, #headline, #innertop, #innerbottom, #sidebar-a .module, #sidebar-b .module, #bottom-a, #bottom-b, #bottom-c, #bottom-d {max-width:%s;background:%s;}', $siteWidth, $templateBackground);
        $style[] = sprintf('#content {padding:%s;}', $this->params->get('sitePadding', 10) . 'px');
        $style[] = sprintf('.wrapper {max-width:%s;}', $siteWidth);
        $style[] = sprintf('#bottom-a, #bottom-b, #bottom-c, #bottom-d {padding:%s;}', $this->params->get('sitePadding', 10) . 'px');
        $style[] = sprintf('#sidebar-a .module, #sidebar-b .module {padding:%s;margin-bottom:%s;}', $this->params->get('sitePadding', 10) . 'px', $this->params->get('sitePadding', 10) . 'px');
        $style[] = sprintf('#main {margin:%s 0;}', $this->params->get('sitePadding', 10) . 'px');
        $style[] = sprintf('h1 ,h2 ,h3,h4, h5 {color:%s;}', $this->params->get('fontColorHeadline', $headlineFontColor));
        $style[] = sprintf('h1 ,.h1 {font-size:%s;}', $this->params->get('h1Size', '40') . 'px');
        $style[] = sprintf('h2 ,.h2 {font-size:%s;}', $this->params->get('h2Size', '35') . 'px');
        $style[] = sprintf('h3 ,.h3 ,h4 ,.h4 ,h5 ,.h5 {font-size:%s;}', $this->params->get('hSize', '30') . 'px');
        $style[] = sprintf('h1 a, h2 a, h3 a, h4 a, h5 a {color:%s;}', $this->params->get('bodyHAColor', $defaultColor));
        $style[] = sprintf('.container { width:100%%;max-width:%s;}', $this->params->get('pageMaxWidth', '1000') . 'px');
        $style[] = sprintf('.pagination > li > a, .pagination > li > span,a {color:%s;}', $this->params->get('aColor', $defaultColor));
        $style[] = sprintf('.pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {border-color:%s;background-color:%s;}', $this->params->get('aColor', $defaultInverse), $this->params->get('aColor', $defaultColor));
        $style[] = sprintf('.btn-primary {color:%s;background-color:%s;border-color:%s;}', $this->params->get('buttonColor', $defaultInverse), $this->params->get('buttonBg', $defaultColor), $this->params->get('buttonBg', $defaultColor));
        $style[] = sprintf('.camera_prev, .camera_next, .camera_stop, .camera_play {background-color:%s!important;}', $defaultColor);

        $style[] = sprintf('.home-aktuelles .items > .item {border-bottom-color:%s; }', $bodyBackground);
         $style[] = sprintf('body.news .blog-items .blog-item {border-color:%s; }', $bodyBackground);
        $style[] = sprintf('.qldropdown > li > ul > li > a {border-color:%s; }', $bodyBackground);

        $style[] = sprintf('.tags .btn {background-color:%s;}', $bodyBackground);
        $style[] = sprintf('.qldropdown a {color:#fff;padding:5px 15px; display:block;}', $templateBackground);
        $style[] = sprintf('.qldropdown li.active > a, .btn:focus, a:focus, .qldropdown a:focus {background:%s;color:%s;}', $activeBackground, $fontColor);
        $style[] = sprintf('a h1:focus, a h2:focus, a h3:focus, a:focus h1, a:focus h2, a:focus h3 {background:%s;}', $activeBackground);
        $style[] = sprintf('.qldropdown a:focus > * {color:%s;}', $fontColor);
        $style[] = sprintf('.slideshowck *:focus {background-color:%s!important;}', $activeBackground);
        $style[] = sprintf('.qldropdown ul {background-color:%s;}', $defaultColor);
        $style[] = sprintf('.jb-decline.link {color:%s;}', $defaultColor);

        $this->templateStyle = implode("\n", $style);
    }

    private function initBodyClass()
    {
        $class = [];
        $menu = $this->app->getMenu()->getActive();
        if (is_object($menu)) $class[] = $menu->getParams()->get('pageclass_sfx');
        $class[] = 'option-' . $this->input->getCmd('option', '');
        $class[] = 'userstate-' . $this->logged;
        $class[] = 'view-' . $this->input->getCmd('view', '');
        $class[] = 'layout-' . $this->input->getCmd('layout', '');
        $class[] = 'task-' . $this->input->getCmd('task', '');
        $class[] = 'itemid-' . $this->input->getCmd('Itemid', '');
        $class[] = 'editing-' . $this->authorized ? '1' : '0';
        $class[] = 'device-' . $this->device;
        $class[] = 'browser-' . $this->browser;
        $class[] = 'mobile-' . $this->mobile;
        $class[] = 'language-' . $this->app->getLanguage()->getTag();;
        $this->classBody = implode(' ', $class);
    }

    public function getBodyClass(): string
    {
        return $this->classBody;
    }

    public function getLanguage(): string
    {
        return $this->app->getLanguage()->getTag();
    }

    public function getTemplateStyles(): string
    {
        return $this->templateStyle;
    }

    public function getWidthContent(): int
    {
        return $this->widthContent;
    }

    public function getWidthSidebarA(): int
    {
        return $this->widthSidebarA;
    }

    public function getWidthSidebarB(): int
    {
        return $this->widthSidebarB;
    }

    public function getWidthAll(): int
    {
        return $this->widthAll;
    }

    private function checkMobile(): bool
    {
        JLoader::import('joomla.environment.browser');
        $browser = JBrowser::getInstance();
        $agent = $browser->getAgentString();
        $this->device = 'desktop';
        $this->mobile = 0;
        $this->browser = 0;
        preg_match('~(firefox|safari|chrome|msie|trident)~i', $agent, $matches);
        if (isset($matches[0])) $this->browser = strtolower($matches[0]);
        preg_match('~(ipad|ipod|iphone|android)~i', $agent, $matches);
        if (!isset($matches[0])) return false;
        $this->device = strtolower($matches[0]);
        //die($this->device);
        $this->mobile = true;
        return true;
    }

    public function initPageSections()
    {
        // get value and verify if integer given
        $pageSectionValue = explode(':', $this->params->get('pageSection'));
        $pageSectionValue = array_filter($pageSectionValue, function($item) { return is_numeric($item) && is_integer($item);});
        $pageSectionValue = (count(self::PAGE_SECTION_DEFAULT) !== count($pageSectionValue))
            ? [$this->getWidthSidebarA(), $this->getWidthContent(), $this->getWidthSidebarB()]
            : self::PAGE_SECTION_DEFAULT;

        // check if everything sums up to a max of 12
        if (array_sum(self::PAGE_SECTION_DEFAULT) < array_sum($pageSectionValue)) $pageSectionValue = self::PAGE_SECTION_DEFAULT;

        // finally set page section
        $this->pageSection = $pageSectionValue;
        $this->widthContent  = $this->pageSection[self::PAGE_SECTION_CONTENT];
        $this->widthSidebarA = $this->pageSection[self::PAGE_SECTION_SIDEBAR_A];
        $this->widthSidebarB = $this->pageSection[self::PAGE_SECTION_SIDEBAR_B];

        if (!$this->template->countModules('sidebar-a')) {
            $this->widthContent += $this->widthSidebarA;
            $this->widthSidebarA = 0;
        }
        if (!$this->template->countModules('sidebar-b')) {
            $this->widthContent += $this->widthSidebarB;
            $this->widthSidebarB = 0;
        }
        $this->widthAll = $this->widthContent + $this->widthSidebarA + $this->widthSidebarB;
    }

    public function favicon()
    {
        if ('' == trim($this->params->get('favicon')) || !file_exists(JPATH_ROOT . '/' . $this->params->get('favicon'))) {
            return;
        }
        // $this->document->addFavicon(\JUri::base(true) . '/' . $this->params->get('favicon', ''));
    }

    public static function getErrorLink(ErrorDocument $errorDocument)
    {
        $menuitem = Factory::getApplication()->getMenu()->getItem($errorDocument->params->get('errorpage', 0));
        $protocol = $_SERVER['REQUEST_SCHEME'] ?? 'https';
        $url = $_SERVER['HTTP_HOST'] ?? '';
        return sprintf('%s://%s/index.php/%s', $protocol, $url, $menuitem->alias ?? '');
    }
}