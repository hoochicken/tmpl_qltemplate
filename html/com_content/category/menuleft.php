<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// JHtml::_('behavior.caption');

$dispatcher = Joomla\CMS\Factory::getApplication()->getDispatcher();

$this->category->text = $this->category->description;
$results = Joomla\CMS\Factory::getApplication()->triggerEvent('onContentPrepare', array($this->category->extension . '.categories', &$this->category, &$this->params, 0));
$this->category->description = $this->category->text;

$results = Joomla\CMS\Factory::getApplication()->triggerEvent('onContentAfterTitle', array($this->category->extension . '.categories', &$this->category, &$this->params, 0));
$afterDisplayTitle = trim(implode("\n", $results));

$results = Joomla\CMS\Factory::getApplication()->triggerEvent('onContentBeforeDisplay', array($this->category->extension . '.categories', &$this->category, &$this->params, 0));
$beforeDisplayContent = trim(implode("\n", $results));

$results = Joomla\CMS\Factory::getApplication()->triggerEvent('onContentAfterDisplay', array($this->category->extension . '.categories', &$this->category, &$this->params, 0));
$afterDisplayContent = trim(implode("\n", $results));

/** @var $params JRegistry */

$strPageclass = JFactory::getApplication()->getMenu()->getActive()->getParams()->get('pageclass_sfx', 'default');
$arrPageclassPart = [];
if(false !== strpos($strPageclass, 'hide')) {
    $arrPageclass = explode(' ', $strPageclass);
    foreach($arrPageclass as $strPageclassPart) {
        if(false === strpos($strPageclassPart, 'hide')) {
            continue;
        }
        $arrPageclassPart = explode('-', $strPageclassPart);
    }
}
?>
<div class="blog<?= $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1> <?= $this->escape($this->params->get('page_heading')); ?> </h1>
        </div>
    <?php endif; ?>

    <?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
        <h2> <?= $this->escape($this->params->get('page_subheading')); ?>
            <?php if ($this->params->get('show_category_title')) : ?>
                <span class="subheading-category"><?= $this->category->title; ?></span>
            <?php endif; ?>
        </h2>
    <?php endif; ?>
    <?= $afterDisplayTitle; ?>

    <?php if ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
        <?php $this->category->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
        <?= $this->category->tagLayout->render($this->category->tags->itemTags); ?>
    <?php endif; ?>

    <?php if ($beforeDisplayContent || $afterDisplayContent || $this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
        <div class="category-desc clearfix">
            <?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
                <img src="<?= $this->category->getParams()->get('image'); ?>"
                     alt="<?= htmlspecialchars($this->category->getParams()->get('image_alt'), ENT_COMPAT, 'UTF-8'); ?>"/>
            <?php endif; ?>
            <?= $beforeDisplayContent; ?>
            <?php if ($this->params->get('show_description') && $this->category->description) : ?>
                <?= JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
            <?php endif; ?>
            <?= $afterDisplayContent; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
        <?php if ($this->params->get('show_no_articles', 1)) : ?>
            <p><?= JText::_('Diese Seite befindet sich noch im Aufbau.'); ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <?php $leadingcount = 0; ?>

      <?php // echo '</pre>'; print_r($this->lead_items); echo '</pre>'; ?>

    <?php if (!empty($this->lead_items)) :
    $strLeadingId = 'category-' . $this->category->id;
    ?>
    <div class="items-leading clearfix">
        <div id="qltab-<?= $strLeadingId; ?>" class="qltabs_container default  vertical qltabsWidth25 fadein" style="">
            <nav class="qltabs_head">
                <ul>
                <?php foreach ($this->lead_items as &$item) :
                    if(!isset($arrPageclassPart) || in_array($item->alias, $arrPageclassPart)) {
                        continue;
                    }?>
                    <?php $arrTitle = explode('|', $item->title); ?>
                    <li class="qltab_head qltabqltab-<?= $strLeadingId; ?>" id="qltabqltab-<?= $strLeadingId; ?>-<?= $item->id?>">
                        <a href="#" role="button" tabindex="0" class="inner" aria-label="<?= preg_replace('([^0-9a-zA-Z-_ ]*)', '', $arrTitle[0] ?? ''); ?>">
                        <?= $arrTitle[0]; ?>
                        </a>
                    </li>
            <?php $leadingcount++; ?>
            <?php endforeach; ?>
                </ul>
            </nav>
        <div class="qltabs">
            <?php foreach ($this->lead_items as &$item) : ?>
            
              <?php $canEdit = $item->params->get('access-edit'); ?>
              <div class="qltab_content" id="qltabqltab-<?= $strLeadingId; ?>-<?= $item->id?>_content" style="display: none;">
              		<?php if ($canEdit) : ?>
	             		<p class="edit pull-right"><?= JHtml::_('icon.edit', $item, $params); ?></p>
	          		<?php endif; ?>
                    
              		<?php $arrTitle = explode('|', $item->title);
                    $strTitle = $arrTitle[0];
                    if(isset($arrTitle[1])) $strTitle = trim($arrTitle[1]); ?>
                    <h2><?= $strTitle; ?></h2>
                    <?php
                    $params = new JRegistry($item);
                    $arrParamsDispatcher = ['com_content.article', &$item, &$params, 0];
                    $item->text = $item->introtext;
                    $dispatcher = Joomla\CMS\Factory::getApplication()->getDispatcher();
                    $event = new Joomla\Event\Event('onContentPrepare', $arrParamsDispatcher);
                    $res = $dispatcher->dispatch('onCheckAnswer', $event);
                    $item->introtext = $item->text;
                    echo $item->introtext;

                    ?>
                </div>
                <?php $leadingcount++; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div><!-- end items-leading -->
<?php endif; ?>

<?php
$introcount = count($this->intro_items);
$counter = 0;
