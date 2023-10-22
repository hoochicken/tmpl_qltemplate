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

class JFormFieldGeneraluse extends JFormField
{
    /**
     * The form field type.
     *
     * @var  string
     * @since 1.6
     */
    protected $type = 'generaluse'; //the form field type see the name is the same
    /**
     * Method to retrieve the lists that resides in your application using the API.
     *
     * @return array The field option objects.
     * @since 1.6
     */
    protected function getInput()
    {

        if(3==$this->value);
        elseif(1==$this->value)JFactory::getDocument()->addStyleSheet(JUri::root().'/templates/qlproto/css/admin/common.css');
        elseif(2==$this->value)JFactory::getDocument()->addStyleSheet(JUri::root().'/templates/qlproto/css/admin/onepager.css');
        else JFactory::getDocument()->addStyleSheet(JUri::root().'/templates/qlproto/css/admin/none.css');

        $html = array();

        // Initialize some field attributes.
        $class     = !empty($this->class) ? ' class="radio ' . $this->class . '"' : ' class="radio"';
        $required  = $this->required ? ' required aria-required="true"' : '';
        $autofocus = $this->autofocus ? ' autofocus' : '';
        $disabled  = $this->disabled ? ' disabled' : '';
        $readonly  = $this->readonly;

        // Start the radio field output.
        $html[] = '<fieldset id="' . $this->id . '"' . $class . $required . $autofocus . $disabled . ' >';

        // Get the field options.
        $options = $this->getOptions();

        // Build the radio field output.
        foreach ($options as $i => $option)
        {
            // Initialize some option attributes.
            $checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
            $class = !empty($option->class) ? ' class="' . $option->class . '"' : '';

            $disabled = !empty($option->disable) || ($readonly && !$checked);

            $disabled = $disabled ? ' disabled' : '';

            // Initialize some JavaScript option attributes.
            $onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';
            $onchange = !empty($option->onchange) ? ' onchange="' . $option->onchange . '"' : '';

            $html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '" value="'
                . htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $required . $onclick
                . $onchange . $disabled . ' />';

            $html[] = '<label for="' . $this->id . $i . '"' . $class . ' >'
                . JText::alt($option->text, preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)) . '</label>';

            $required = '';
        }

        // End the radio field output.
        $html[] = '</fieldset>';

        return implode($html);
    }
    protected function getOptions()
    {
        $options = array();
        $options[0]=new stdClass();
        $options[0]->value=0;
        $options[0]->text=JText::_('JNONE');
        $options[1]=new stdClass();
        $options[1]->value=1;
        $options[1]->text=JText::_('TPL_QLPROTO_GENERALUSE_COMMON');
        $options[2]=new stdClass();
        $options[2]->value=2;
        $options[2]->text=JText::_('TPL_QLPROTO_GENERALUSE_ONEPAGER');
        $options[3]=new stdClass();
        $options[3]->value=3;
        $options[3]->text=JText::_('JALL');

        return $options;
    }
}