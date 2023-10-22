<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Rule class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.2
 */
class JFormRuleRgba extends JFormRule
{
	/**
	 * Method to test for a valid color in hexadecimal.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 * @param   JRegistry         $input    An optional JRegistry object with the entire data set to validate against the entire form.
	 * @param   JForm             $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   11.2
	 */
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{
        if(!isset($value->opacity))$value->opacity='1.0';
        if(!isset($value->color)) $value->color='#ffffff';

        if (0==count($value))return false;

        $rgb=$this->html2rgb($value->color,2);

        $input=JFactory::getApplication()->input;
        $name=$element->attributes()->name;
        $rgba='rgba('.$rgb.','.$value->opacity.')';
        $jform=$input->getData('jform');
        $jform['params'][(string)$name]=$rgba;
        $input->set('jform',$jform);
        return true;
	}
    private function html2rgb($color,$type=1)
    {
        if ($color[0] == '#') $color = substr($color, 1);
        if (strlen($color) == 6)
        {
            list($r, $g, $b) = array($color[0].$color[1],
                $color[2].$color[3],
                $color[4].$color[5]);
        }
        elseif (strlen($color) == 3) list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
        else return array(255,255,255);//false;
        $r=hexdec($r);
        $g=hexdec($g);
        $b=hexdec($b);
        if (1==$type)return array($r, $g, $b);
        elseif (2==$type)return $r.','.$g.','.$b;
    }
}
