<?php
/**
 * @package		mod_form
 * @copyright	Copyright (C) 2013 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldRgba extends JFormField
{

    /**
     * The form field type.
     *
     * @var    string
     * @since  11.3
     */
    protected $type = 'rgba';
    /**
     * Method to retrieve the lists that resides in your application using the API.
     *
     * @return array The field option objects.
     * @since 1.6
     */
    protected function getInput()
    {
        $value='';
        $opacity='';
        if (isset($this->value['color']))$value=$this->value['color'];
        if (isset($this->value['opacity']))$opacity=$this->value['opacity'];
        $html=array();
        $html[]='<table>';
        $html[]='<tr>';
        $html[]='<th>';
        $html[]=JText::_('TPL_QLPROTO_COLOR');;
        $html[]='</th>';
        $html[]='<th>';
        $html[]=JText::_('TPL_QLPROTO_OPACITY');;
        $html[]='</th>';
        $html[]='</tr>';
        $html[]='<tr>';
        $html[]='<td>';
        $html[]=$this->getInputColor($value);
        $html[]='</td>';
        $html[]='<th>';
        $html[]=$this->getInputOpacity($opacity);
        $html[]='</td>';
        $html[]='</td>';
        $html[]='</table>';

        return implode("\n",$html);
    }
    private function XgetInputColor($value)
    {
        return '<input style="padding:0;;" type="color" name="'.$this->name.'[color]" id="'.$this->id.'" value="'.$value.'" />';
    }
    private function getInputOpacity($value)
    {
        $html=array();
        $html[]='<select style="width:90px;" name="'.$this->name.'[opacity]">';
        for($f=1;$f>=-0.01;$f=$f-0.02)
        {
            $selected='';
            if((string)$value==(string)$f) $selected='selected="selected"';
            $html[]='<option value="'.$f.'"';
            $html[]=$selected;
            $html[]='>'.(number_format($f,2)*100).' %</option>';
            unset($selected);
        }
        $html[]='</select>';
        return implode("\n",$html);
    }
    /**
     * The control.
     *
     * @var    mixed
     * @since  3.2
     */
    protected $control = 'hue';

    /**
     * The position.
     *
     * @var    mixed
     * @since  3.2
     */
    protected $position = 'right';

    /**
     * The colors.
     *
     * @var    mixed
     * @since  3.2
     */
    protected $colors;

    /**
     * The split.
     *
     * @var    integer
     * @since  3.2
     */
    protected $split = 3;

    /**
     * Method to get certain otherwise inaccessible properties from the form field object.
     *
     * @param   string  $name  The property name for which to the the value.
     *
     * @return  mixed  The property value or null.
     *
     * @since   3.2
     */
    public function __get($name)
    {
        switch ($name)
        {
            case 'control':
            case 'exclude':
            case 'colors':
            case 'split':
                return $this->$name;
        }

        return parent::__get($name);
    }

    /**
     * Method to set certain otherwise inaccessible properties of the form field object.
     *
     * @param   string  $name   The property name for which to the the value.
     * @param   mixed   $value  The value of the property.
     *
     * @return  void
     *
     * @since   3.2
     */
    public function __set($name, $value)
    {
        switch ($name)
        {
            case 'split':
                $value = (int) $value;
            case 'control':
            case 'exclude':
            case 'colors':
                $this->$name = (string) $value;
                break;

            default:
                parent::__set($name, $value);
        }
    }

    /**
     * Method to attach a JForm object to the field.
     *
     * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
     * @param   mixed             $value    The form field value to validate.
     * @param   string            $group    The field name group control value. This acts as as an array container for the field.
     *                                      For example if the field has name="foo" and the group value is set to "bar" then the
     *                                      full field name would end up being "bar[foo]".
     *
     * @return  boolean  True on success.
     *
     * @see     JFormField::setup()
     * @since   3.2
     */
    public function setup(SimpleXMLElement $element, $value, $group = null)
    {
        $return = parent::setup($element, $value, $group);

        if ($return)
        {
            $this->control  = isset($this->element['control']) ? (string) $this->element['control'] : 'hue';
            $this->position = isset($this->element['position']) ? (string) $this->element['position'] : 'right';
            $this->colors   = (string) $this->element['colors'];
            $this->split    = isset($this->element['split']) ? (int) $this->element['split'] : 3;
        }

        return $return;
    }

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   11.3
     */
    protected function getInputColor($value)
    {
        // Translate placeholder text
        $hint = $this->translateHint ? JText::_($this->hint) : $this->hint;

        // Control value can be: hue (default), saturation, brightness, wheel or simple
        $control = $this->control;

        // Position of the panel can be: right (default), left, top or bottom
        $position = ' data-position="' . $this->position . '"';

        $onchange  = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';
        $class     = $this->class;
        $required  = $this->required ? ' required aria-required="true"' : '';
        $disabled  = $this->disabled ? ' disabled' : '';
        $autofocus = $this->autofocus ? ' autofocus' : '';

        /*QL CHANGES $this->value to $value*/
        $color = strtolower($value);

        if (!$color || in_array($color, array('none', 'transparent')))
        {
            $color = 'none';
        }
        elseif ($color['0'] != '#')
        {
            $color = '#' . $color;
        }

        if ($control == 'simple')
        {
            $class = ' class="' . trim('simplecolors chzn-done ' . $class) . '"';
            JHtml::_('behavior.simplecolorpicker');

            $colors = strtolower($this->colors);

            if (empty($colors))
            {
                $colors = array(
                    'none',
                    '#049cdb',
                    '#46a546',
                    '#9d261d',
                    '#ffc40d',
                    '#f89406',
                    '#c3325f',
                    '#7a43b6',
                    '#ffffff',
                    '#999999',
                    '#555555',
                    '#000000'
                );
            }
            else
            {
                $colors = explode(',', $colors);
            }

            $split = $this->split;

            if (!$split)
            {
                $count = count($colors);

                if ($count % 5 == 0)
                {
                    $split = 5;
                }
                else
                {
                    if ($count % 4 == 0)
                    {
                        $split = 4;
                    }
                }
            }

            $split = $split ? $split : 3;

            $html = array();
            $html[] = '<select name="' . $this->name . '[color]" id="' . $this->id . '"' . $disabled . $required
                . $class . $position . $onchange . $autofocus . ' style="visibility:hidden;width:22px;height:1px">';

            foreach ($colors as $i => $c)
            {
                $html[] = '<option' . ($c == $color ? ' selected="selected"' : '') . '>' . $c . '</option>';

                if (($i + 1) % $split == 0)
                {
                    $html[] = '<option>-</option>';
                }
            }

            $html[] = '</select>';

            return implode('', $html);
        }
        else
        {
            $class        = ' class="' . trim('minicolors ' . $class) . '"';
            $control      = $control ? ' data-control="' . $control . '"' : '';
            $readonly     = $this->readonly ? ' readonly' : '';
            $hint         = $hint ? ' placeholder="' . $hint . '"' : ' placeholder="#rrggbb"';
            $autocomplete = !$this->autocomplete ? ' autocomplete="off"' : '';

            // Including fallback code for HTML5 non supported browsers.
            JHtml::_('jquery.framework');
            JHtml::_('script', 'system/html5fallback.js', false, true);

            JHtml::_('behavior.colorpicker');

            return '<input type="text" name="' . $this->name . '[color]" id="' . $this->id . '"' . ' value="'
                . htmlspecialchars($color, ENT_COMPAT, 'UTF-8') . '"' . $hint . $class . $position . $control
                . $readonly . $disabled . $required . $onchange . $autocomplete . $autofocus . '/>';
        }
    }
 }