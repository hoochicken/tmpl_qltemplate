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

class JFormFieldFont extends JFormField
{
    /**
     * The form field type.
     *
     * @var  string
     * @since 1.6
     */
    protected $type = 'font'; //the form field type see the name is the same
    private $fontSize=array();
    private $arr_fonts=array
    (
        'Arial'=>'Arial,Helvetica,sans-serif',
        'ArialB'=>'Arial Black,Gadget,sans-serif',
        'Comic'=>'Comic Sans MS,cursive,sans-serif',
        'Courier'=>'Courier New,Courier,monospace',
        'Georgia'=>'Georgia,serif',
        'Impact'=>'Impact,Charcoal,sans-serif',
        'Lucida-Sans-Unicode'=>'Lucida Sans Unicode,Lucida Grande,sans-serif',
        'Lucida-Console'=>'Lucida Console,Monaco,monospace',
        'Palatino'=>'Palatino Linotype,Book Antiqua,Palatino,serif',
        'Tahoma'=>'Tahoma,Geneva,sans-serif',
        'Times'=>'Times New Roman,Times,serif',
        'Trebuchet'=>'Trebuchet MS,Helvetica,sans-serif',
        'Verdana'=>'Verdana,Geneva,sans-serif',
    );
    /**
     * Method to retrieve the lists that resides in your application using the API.
     *
     * @return array The field option objects.
     * @since 1.6
     */
    protected function getInput()
    {
        if(!isset($this->value) OR !is_array($this->value))$this->value=array();
        $values=array
        (
            /*'field name section' => 'default value'*/
            'fontsize'=>12,
            'fontvariant'=>'normal',
            'font'=>'Arial',
            'googlefont'=>'',
            'letterspacing'=>'0',
            'color'=>'0',
        );
        foreach($values as $k => $v)if(!isset($this->value[$k]))$this->value[$k]=$v;

        $html=array();
        $html[]='<table>';
        $html[]='<tr>';
        $html[]='<td>';
        $html[]=JText::_('TPL_QLPROTO_FONTSIZE_LABEL');
        $html[]='</td>';
        $html[]='<td>';
        $html[]=JText::_('TPL_QLPROTO_FONTVARIANT_LABEL');
        $html[]='</td>';
        $html[]='</tr>';
        $html[]='<tr>';
        $html[]='<td>';
        $html[]=$this->getInputFontsize($this->value['fontsize']);
        $html[]='<br/>';
        $html[]='<br/>';
        $html[]='</td>';
        $html[]='<td>';
        $html[]=$this->getInputFontvariant($this->value['fontvariant']);
        $html[]='<br/>';
        $html[]='<br/>';
        $html[]='</td>';
        $html[]='</tr>';
        $html[]='<tr>';
        $html[]='<td>';
        $html[]=JText::_('TPL_QLPROTO_FONT_LABEL');
        $html[]='</td>';
        $html[]='<td>';
        $html[]=JText::_('TPL_QLPROTO_GOOGLEFONT_LABEL');
        $html[]='</td>';
        $html[]='</tr>';
        $html[]='<tr>';
        $html[]='<td>';
        $html[]=$this->getInputFont($this->value['font']);
        $html[]='<br/>';
        $html[]='<br/>';
        $html[]='</td>';
        $html[]='<td>';
        // $html[]=$this->getInputGooglefont($this->value['googlefont']);
        $html[]='<br/>';
        $html[]='<br/>';
        $html[]='</td>';
        $html[]='</tr>';
        $html[]='<tr>';
        $html[]='<td>';
        $html[]=JText::_('TPL_QLPROTO_LETTERSPACING_LABEL');
        $html[]='</td>';
        $html[]='<td>';
        $html[]=JText::_('TPL_QLPROTO_FONTCOLOR_LABEL');
        $html[]='</td>';
        $html[]='</tr>';
        $html[]='<tr>';
        $html[]='<td>';
        $html[]=$this->getInputLetterspacing($this->value['letterspacing']);
        $html[]='</td>';
        $html[]='<td>';
        $html[]=$this->getInputColor($this->value['color']);
        $html[]='</td>';
        $html[]='</tr>';
        $html[]='</table>';
        return implode("\n",$html);
    }

    private function getInputFont($value='')
    {
        $name=$this->getDaName('font');
        $html=array();
        $html[]='<select name="'.$name.'">';
        $html[]="\n";
        foreach($this->arr_fonts as $k=>$v)
        {
            $html[]='<option ';
            if (isset($value) AND ''!=$value AND $value==$k) $html[]='selected="selected" ';
            $html[]='value="'.$k.'">'.$v.'</option>';
            $html[]="\n";
        }
        $html[]="\n";
        $html[]='</select>';
        //return $html;
        return implode("\n",$html);
    }
    private function getInputFontsize($value='')
    {
        $name=$this->getDaName('fontsize');
        $fontsize=range(8,50);
        $html=array();
        $html[]='<select name="'.$name.'">';
        $html[]="\n";
        foreach($fontsize as $k => $v)
        {
            $html[]='<option ';
            if (''!=$value AND $value==$v) $html[]='selected="selected" ';
            $html[]='value="'.$v.'">'.$v.'px</option>';
            $html[]="\n";
        }
        $html[]="\n";
        $html[]='</select>';
        return implode("\n",$html);
        return $html;
    }

    private function getInputGooglefont($value='')
    {
        return;
        $name=$this->getDaName('googlefont');
        $html=array();
        $html[]='<input type="text" name="'.$name.'" value="'.$value.'"/>';
        return implode("\n",$html);
        return $html;
    }
    private function getInputFontvariant($value='')
    {
        $name=$this->getDaName('fontvariant');
        $fontvariant=array
        (
            'normal'=>JText::_('JNONE'),
            'small-caps'=>JText::_('TPL_QLPROTO_FONTVARIANT_CAPSCASE'),
            'lowercase'=>JText::_('TPL_QLPROTO_FONTVARIANT_LOWERCASE'),
            'uppercase'=>JText::_('TPL_QLPROTO_FONTVARIANT_UPPERCASE'),
        );
        $html=array();
        $html[]='<select name="'.$name.'">';
        $html[]="\n";
        foreach($fontvariant as $k => $v)
        {
            $html[]='<option ';
            if (''!=$value AND $value==$k) $html[]='selected="selected" ';
            $html[]='value="'.$k.'">'.$v.'</option>';
            $html[]="\n";
        }
        $html[]="\n";
        $html[]='</select>';
        return implode("\n",$html);
        return $html;
    }
    private function getInputLetterspacing($value='')
    {
        $name=$this->getDaName('letterspacing');
        $letterspacing=range(0,25);
        $html=array();
        $html[]='<select name="'.$name.'">';
        $html[]="\n";
        foreach($letterspacing as $k => $v)
        {
            $html[]='<option ';
            if (''!=$value AND $value==$v) $html[]='selected="selected" ';
            $html[]='value="'.$v.'">'.$v.'px</option>';
            $html[]="\n";
        }
        $html[]="\n";
        $html[]='</select>';
        return implode("\n",$html);
        return $html;
    }
    private function getDaName($name)
    {
        return $name=$this->name.'['.$name.']';
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