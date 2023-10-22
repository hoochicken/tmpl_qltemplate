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

class JFormFieldQlform extends JFormField
{
    /**
     * The form field type.
     *
     * @var  string
     * @since 1.6
     */
    protected $type = 'qlform'; //the form field type see the name is the same
	/**
     * Method to retrieve the lists that resides in your application using the API.
     *
     * @return array The field option objects.
     * @since 1.6
     */
    protected function getInput()
    {
        $this->db=JFactory::getDbo();
        $valueModuleId='';
        if (is_array($this->value) AND isset($this->value['moduleId']))$valueModuleId=$this->value['moduleId'];else $valueModuleId='';
        if (is_array($this->value) AND isset($this->value['recipients']))$valueTextarea=$this->value['recipients'];else $valueTextarea='';
        if(false==$this->checkModuleExists($valueModuleId))
        {
            $html=array();
            $html[]='Module-Id of qlform';
            $html[]='<br/>';
            $html[]='<input type="text" name="'.$this->name.'[moduleId]" value="'.$valueModuleId.'" />';
            return implode ("\n",$html);
        }
        $this->saveValueToModule($valueModuleId,$valueTextarea);
        $html=array();
        $html[]='<textarea style="height:50px;" name="'.$this->name.'[recipients]">';
        $html[]=$valueTextarea;
        $html[]='</textarea>';
        $html[]='<br/>';
        $html[]='Module-Id of qlform';
        $html[]='<br/>';
        $html[]='<input type="text" name="'.$this->name.'[moduleId]" value="'.$valueModuleId.'" />';
        return implode ("\n",$html);
    }
    private function saveValueToModule($id,$value)
    {
        if(!isset($this->form->getData()->get('params')->qlform_override) OR 1!=$this->form->getData()->get('params')->qlform_override) return;
		$params=$this->getParamsFromModule($id);
        $params->emailrecipient=trim($value);
        $this->data->params=json_encode($params);
        $this->db->updateObject('#__modules',$this->data,'id');
    }
    private function getParamsFromModule($id)
    {
        $query=$this->db->getQuery(true);
        $query->select('*');
        $query->from('#__modules');
        $query->where('`id`=\''.$id.'\' AND `module` LIKE \'%qlform%\'');
        $this->db->setQuery($query);
        $this->data=$this->db->loadObject();
        if(!isset($this->data->id) OR $id!=$this->data->id)return false;
        $params=json_decode($this->data->params);
        return $params;
    }
    private function checkModuleExists($id)
    {
        if(''==$id OR !is_numeric($id)) return false;
        $query=$this->db->getQuery(true);
        $query->select('*');
        $query->from('#__modules');
        $query->where('`id`=\''.$id.'\' AND `module` LIKE \'%qlform%\'');
        $this->db->setQuery($query);
        $this->data=$this->db->loadObject();
        if(!isset($this->data->id) OR $id!=$this->data->id) return false;
        $params=json_decode($this->data->params);
        return $params;
    }
}