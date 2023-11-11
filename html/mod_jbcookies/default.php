<?php
/**
 * @package			Joomla.Site
 * @subpackage		Modules - mod_jbcookies
 * 
 * @author			JoomBall! Project
 * @link			http://www.joomball.com
 * @copyright		Copyright © 2011-2018 JoomBall! Project. All Rights Reserved.
 * @license			GNU/GPL, http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;


$script = sprintf('
    jQuery(document).ready(function () { 
    	function setCookie(c_name,value,exdays,domain) {
			if (domain != "") {domain = "; domain=" + domain}

			var exdate = new Date();
			exdate.setDate(exdate.getDate() + exdays);
			var c_value = escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString()) + "; path=/" + domain;

			document.cookie = c_name + "=" + c_value;
		}

		var $jb_cookie = jQuery(".jb-cookie"),
			cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)jbcookies\s*\=\s*([^;]*).*$)|^.*$/, "$1");

		if (cookieValue === "") { // NO EXIST
			$jb_cookie.delay(1000).slideDown("fast");
		    %s
		}

		jQuery(".jb-accept").click(function() {
			setCookie("jbcookies","yes", %s,"%s");
			$jb_cookie.slideUp("slow");
			jQuery(".jb-cookie-decline").fadeIn("slow", function() {});
		});

		jQuery(".jb-decline").click(function() {
			jQuery(".jb-cookie-decline").fadeOut("slow", function() {
				%s
			});
			setCookie("jbcookies","",0,"%s");
			$jb_cookie.delay(1000).slideDown("fast");
		});
    });',
    ($params->get('show_decline', 1)) ? '' : 'jQuery(".jb-cookie-decline").fadeIn("slow", function() {});',
    $params->get('duration_cookie_days', 90),
    trim($domain),
    ($modal_framework == 'bootstrap' && $framework_version != 5) ? '' : 'jQuery(".jb-cookie-decline").find(".hasTooltip").tooltip("hide");',
    trim($domain)
);

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->addInlineScript($script);

?>
<!--googleoff: all-->
<?php if ($params->get('show_decline', 1)) : ?>
	<div class="jb-cookie-decline <?php echo $moduleclass_sfx; ?> robots-noindex robots-nofollow robots-nocontent" style="display: none;">
		<?php echo $params->get('show_decline_description', 1) ? $text_decline : ''; ?>
		<button title="Hiermit widerrufen Sie Ihre Cookie-Einstellungen" class="btn btn-secondary jb-decline link"><?php echo $aliasButton_decline; ?></button>
	</div>
<?php endif; ?>
<?php if ($modal_framework == 'bootstrap') : ?>
	<!-- Template Default bootstrap -->
	<div class="jb-cookie <?php echo $position; ?> <?php echo $color_background; ?> <?php echo str_replace('btn-', '', $color_links) . $color_border; ?> <?php echo $moduleclass_sfx; ?> robots-noindex robots-nofollow robots-nocontent" style="display: none;">
		<!-- BG color -->
		<div class="jb-cookie-bg <?php echo $color_background; ?>"></div>
	    
		<p class="jb-cookie-title"><?php echo $title; ?></p>
		<p><?php echo $text; ?>
			<?php if($show_info) : ?>
				<?php if($aLink) : ?>
					<a href="<?php echo $item->readmore_link; ?>"><?php echo $aliasLink; ?></a>
				<?php else: ?>
					<?php $toggle = ($framework_version == 5) ? 'data-bs-toggle="modal" data-bs-target="#jbcookies"' : 'data-toggle="modal" data-target="#jbcookies"'; ?>
					<!-- Button to trigger modal -->
					<a href="#jbcookies" <?php echo $toggle; ?>><?php echo $aliasLink; ?></a>
				<?php endif; ?>
			<?php endif; ?>
		</p>
		<div class="jb-accept btn <?php echo $color_links; ?>"><?php echo $aliasButton; ?></div>
	</div>
	
	<?php if($show_info && !$aLink) : ?>
	    <!-- Modal -->
	    <?php if($framework_version == 5) : // For Bootstrap 5 ?>
			<div class="modal robots-noindex robots-nofollow robots-nocontent" id="jbcookies" tabindex="-1">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php echo $header; ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<?php echo $body; ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo Text::_('JLIB_HTML_BEHAVIOR_CLOSE'); ?></button>
						</div>
					</div>
				</div>
			</div>
		<?php elseif(in_array($framework_version, array(3,4))) : // For Bootstrap 3-4 ?>
			<div class="modal robots-noindex robots-nofollow robots-nocontent" id="jbcookies" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<?php if ($framework_version == 3) : ?>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title"><?php echo $header; ?></h4>
							<?php else : ?>
								<h4 class="modal-title"><?php echo $header; ?></h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php endif; ?>
						</div>
						<div class="modal-body">
							<?php echo $body; ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal"><?php echo Text::_('JLIB_HTML_BEHAVIOR_CLOSE'); ?></button>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

<?php else : ?>
	<!-- Template Default uikit -->
	<div class="jb-cookie <?php echo $position; ?> <?php echo $color_background; ?> <?php echo $color_links; ?> <?php echo $moduleclass_sfx; ?> robots-noindex robots-nofollow robots-nocontent" style="display: none;">
		<!-- BG color -->
		<div class="jb-cookie-bg <?php echo $color_background; ?>"></div>
	    
		<p class="cookie-title"><?php echo $title; ?></p>
		<p><?php echo $text; ?>
			<?php if($show_info) : ?>
				<?php if($aLink) : ?>
					<a href="<?php echo $item->readmore_link; ?>"><?php echo $aliasLink; ?></a>
				<?php else: ?>
					<!-- Button to trigger modal -->
					<a href="#jbcookies" data-uk-modal><?php echo $aliasLink; ?></a>
				<?php endif; ?>
			<?php endif; ?>
		</p>
		<div class="jb-accept uk-button uk-button-success <?php echo $color_links; ?>"><?php echo $aliasButton; ?></div>
	</div>
	
	<?php if($show_info and !$aLink) : ?>
	    <!-- Modal -->
		<div id="jbcookies" class="uk-modal robots-noindex robots-nofollow robots-nocontent">
			<div class="uk-modal-dialog uk-modal-dialog-large">
				<button class="uk-modal-close uk-close" type="button"></button>
				<div class="uk-modal-header">
					<h2><?php echo $header; ?></h2>
				</div>
				<?php echo $body; ?>
				<div class="uk-modal-footer uk-text-right">
					<button class="uk-button uk-modal-close" type="button"><?php echo Text::_('JLIB_HTML_BEHAVIOR_CLOSE'); ?></button>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
<!--googleon: all-->