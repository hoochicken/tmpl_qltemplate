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

class JFormFieldBorder extends JFormField
{

    /**
     * The form field type.
     *
     * @var    string
     * @since  11.3
     */
    protected $type = 'Border';

    /**
     * Method to retrieve the lists that resides in your application using the API.
     *
     * @return array The field option objects.
     * @since 1.6
     */
    protected function getInput()
    {
        $width=0;
        $style='solid';
        $color='#000';
        $radius='0';
        $where=array();;
        if (isset($this->value['width']))$width=$this->value['width'];
        if (isset($this->value['style']))$style=$this->value['style'];
        if (isset($this->value['color']))$color=$this->value['color'];
        if (isset($this->value['radius']))$radius=$this->value['radius'];
        if (isset($this->value['where']))$where=$this->value['where'];
        $html=array();
        $html[]='<table>';
        $html[]='<tr>';
        $html[]='<th>';
        $html[]=JTEXT::_('TPL_QLPROTO_WIDTH_LABEL');
        $html[]='</th>';
        $html[]='<th>';
        $html[]=JTEXT::_('TPL_QLPROTO_STYLE_LABEL');
        $html[]='</th>';
        $html[]='<th>';
        $html[]=JTEXT::_('TPL_QLPROTO_COLOR_LABEL');
        $html[]='</th>';
        $html[]='<th>';
        $html[]=JTEXT::_('TPL_QLPROTO_RADIUS_LABEL');
        $html[]='</th>';
        $html[]='<th>';
        $html[]=JTEXT::_('TPL_QLPROTO_WHERE_LABEL');
        $html[]='</th>';
        $html[]='</tr>';
        $html[]='<tr>';
        $html[]='<td>';
        $html[]=$this->getInputWidth($width);
        $html[]='</td>';
        $html[]='<td>';
        $html[]=$this->getInputStyle($style);
        $html[]='</td>';
        $html[]='<td>';
        $html[]=$this->getInputColor($color);
        $html[]='</td>';
        $html[]='<td>';
        $html[]=$this->getInputRadius($radius);
        $html[]='</td>';
        $html[]='<td>';
        $html[]=$this->getInputWhere($where);
        $html[]='</td>';
        $html[]='</tr>';
        $html[]='</table>';

        return implode("\n",$html);
    }
    private function XgetInputColor($value)
    {
        $name='color';
        $name=$this->name.'['.$name.']';
        return '<input style="padding:0;;" type="color" name="'.$name.'" value="'.$value.'" />';
    }
    private function getInputWidth($value)
    {
        $name='width';
        $name=$this->name.'['.$name.']';
        $html=array();
        $html[]='<select style="width:90px;" name="'.$name.'">';
        for($i=0;$i<=50;$i++)
        {
            $selected='';
            $iValue=$i.'px';
            if((string)$value==(string)$iValue) $selected='selected="selected"';
            $html[]='<option value="'.$iValue.'"';
            $html[]=$selected;
            $html[]='>'.$iValue.'</option>';
            unset($selected);
        }
        $html[]='</select>';
        return implode("\n",$html);
    }
    private function getInputStyle($value)
    {
        $name='style';
        $name=$this->name.'['.$name.']';
        $styles=array('solid','dashed','dotted','groove','inset','outset','ridge',);
        $html=array();
        $html[]='<select style="width:90px;" name="'.$name.'">';
        foreach($styles as $k=>$v)
        {

            $selected='';
            if($value==$v) $selected='selected="selected"';
            $html[]='<option value="'.$v.'"';
            $html[]=$selected;
            $html[]='>'.JText::_('TPL_QLPROTO_STYLE_'.strtoupper($v)).'</option>';
            unset($selected);
        }
        $html[]='</select>';
        return implode("\n",$html);
    }
    private function getInputRadius($value)
    {
        $name='radius';
        $name=$this->name.'['.$name.']';
        $html=array();
        $html[]='<select style="width:90px;" name="'.$name.'">';
        for($i=0;$i<=100;$i++)
        {
            $selected='';
            $iValue=$i.'px';
            if((string)$value==(string)$iValue) $selected='selected="selected"';
            $html[]='<option value="'.$iValue.'"';
            $html[]=$selected;
            $html[]='>'.$iValue.'</option>';
            unset($selected);
        }
        $html[]='</select>';
        return implode("\n",$html);
    }
    private function getInputWhere($value)
    {
        $name='where';
        $name=$this->name.'['.$name.']';
        $styles=array('top','bottom','left','right','all','none','top-bottom','left-right','top-left-right','bottom-left-right',);
        $html=array();
        $html[]='<select style="width:90px;" name="'.$name.'">';
        foreach($styles as $k=>$v)
        {

            $selected='';
            if($value==$v) $selected='selected="selected"';
            $html[]='<option value="'.$v.'"';
            $html[]=$selected;
            $html[]='>'.JText::_('TPL_QLPROTO_WHERE_'.strtoupper(str_replace('-','',$v))).'</option>';
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