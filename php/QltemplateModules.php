<?php

namespace Qltemplate\php;

use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

class QltemplateModules
{
    private ?\stdClass $module = null;
    private ?Registry $params = null;
    private array $attribs = [];
    public string $sfx = '';
    public string $content = '';
    public string $hpull = '';

    public function __construct($module, &$params, &$attribs)
    {
        $this->module = $module;
        $this->params = $params;
        $this->attribs = $attribs;
        $this->sfx = (string)$params->get('moduleclass_sfx', '');
    }

    public function getContent(\stdClass $module, Registry $params, array $attribs, string $sfx = ''): string
    {
        if (empty($module->content)) return '';
        if (str_contains($sfx, 'bareback')) return $this->bareback($module, $params, $attribs, $sfx);
        if (str_contains($sfx, 'toggle')) return $this->toggle($module, $params, $attribs, $sfx);
        if (str_contains($sfx, 'responsive-menu')) return $this->navbar($module, $params, $attribs, $sfx);
        else return $this->standard($module, $params, $attribs, $sfx);
    }

    public function bareback(\stdClass $module, Registry $params, array $attribs, string $sfx = ''): string
    {
        return $module->content;
    }

    public function standard(\stdClass $module, Registry $params, array $attribs, string $sfx = ''): string
    {
        $tag = 'div';
        $role = '';
        if (str_contains($sfx, 'menu')) {
            $tag = 'nav';
            $role = 'role="navigation"';
        }

        $strReturn = '';
        $strReturn .= '<' . $tag . ' ' . $role . ' id="module_' . $module->id . '" class="module' . htmlspecialchars($sfx);
        if (!$params->get('bootstrap_size', 0)) $strReturn .= ' span' . $params->get('bootstrap_size', 0);
        $strReturn .= '">';
        if ($module->showtitle) {
            $headerLevel = isset($attribs['headerLevel']) ? (int)$attribs['headerLevel'] : 3;
            if ($module->showtitle) $strReturn .= '<h' . $headerLevel . ' class="module-title">' . $module->title . '</h' . $headerLevel . '>';
        }
        $strReturn .= '<div class="module-content">';
        $strReturn .= $module->content;
        $strReturn .= '</div>';
        $strReturn .= '</' . $tag . '>';
        return $strReturn;
    }

    public function toggle(\stdClass $module, Registry $params, array $attribs, string $sfx = ''): string
    {
        if (empty($module->content)) return '';
        $cssId = 'module_toggle_content_' . $module->id;
        return sprintf('
<div id="module_%s" class="module%s">
    <div class="module-content">
        <div class="d-none d-sm-none d-md-block">%s</div>
        <!--button class="btn btn-primary d-sm-block d-md-none float-right toggle-menu-stuff" type="button" data-bs-toggle="collapse" data-bs-target="#s" aria-expanded="false" aria-controls="s"><strong>&Xi;</strong> Men&uuml;</button-->
        <button class="btn btn-primary d-sm-block d-md-none float-right toggle-menu-stuff" type="button" data-bs-toggle="collapse" data-bs-target="#%s" aria-expanded="false" aria-controls="%s"><i class="fa fa-bars"></i> Men&uuml;</button>
        <div class="collapse" id="%s"><div class="mobile">%s</div></div>
    </div> 
</div>', $module->id, $sfx, $module->content, $cssId, $cssId, $cssId, str_replace('qldropdown horizontal', 'qldropdown mobile', $module->content));
    }

    public function navbar(\stdClass $module, Registry $params, array $attribs, string $sfx = ''): string
    {
        return $this->toggle($module, $params, $attribs, $sfx);
    }
}
