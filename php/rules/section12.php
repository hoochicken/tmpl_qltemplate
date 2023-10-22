<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
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
class JFormRuleSection12 extends JFormRule
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
        try
        {
            $arrValue=explode(':',$value);
            if(3!=count($arrValue)) throw new Exception(JText::_('TPL_QLBOOTSTRAP_MSG_SECTION12INPUTINVALID'));
            $sum=0;
            foreach($arrValue as $k => $v)
            {
                if (!is_numeric($v) AND !is_integer($v))throw new Exception(sprintf(JText::_('TPL_QLBOOTSTRAP_MSG_SECTION12NOINTEGER'),(string)$v));
                $sum+=$v;
            }
            if(!is_numeric($sum) || 12<$sum)throw new Exception(JText::sprintf('TPL_QLBOOTSTRAP_MSG_SECTION12ADDUPTO12',$sum));
            if(12>$sum)JFactory::getApplication()->enqueueMessage(JText::sprintf('TPL_QLBOOTSTRAP_MSG_SECTION12LOWER12',$sum),'notice');
            return true;
        }
        catch (Exception $e)
        {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
            return false;
        }
	}
}
