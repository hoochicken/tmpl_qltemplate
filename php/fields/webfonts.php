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

class JFormFieldWebfonts extends JFormField
{
    /**
     * The form field type.
     *
     * @var  string
     * @since 1.6
     */
    protected $type = 'fonts'; //the form field type see the name is the same
	public $arr_fonts=array
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
        $html='';
        //die($this->value);
        $html.='<select name="'.$this->name.'" id="'.$this->id.'">';
        $html.="\n";
        foreach($this->arr_fonts as $k=>$v)
        {
        	$html.='<option ';
        	if (isset($this->value) AND ''!=$this->value AND $this->value==$k) $html.='selected="selected" ';
        	$html.='value="'.$k.'">'.$v.'</option>';
        	$html.="\n";
        }
        $html.="\n";
        $html.='</select>';
        $html=str_replace('"','',$html);
        return $html;
    }
 }