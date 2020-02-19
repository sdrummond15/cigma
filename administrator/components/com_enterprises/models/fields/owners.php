<?php
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldOwners extends JFormField
{
    protected $type = 'checkboxes';

    protected function getInput()
    {
        //Get form details
        $user = JFactory::getUser();
        $userId = $user->get('id');

        $form = $this->form;
        $created_by = $form->getData()->get('created_by');
        if(!empty($created_by)){
            $userId = $created_by;
        }

        $db = JFactory::getDBO();
        $query = "SELECT id, name FROM #__owners WHERE created_by = $userId ORDER BY name";
        $db->setQuery($query);
        $owners = $db->loadObjectList();

        $var_list = '';
        if(!empty($owners)){
            foreach ($owners as $owner) {
                $checked = $this->getValues($owner->id);
                $var_list .= '<label for="jform_owner_id' . $owner->id . '" class="checkbox"><input name="' . $this->name . '[]" type="checkbox" value="' . $owner->id . '" id="jform_owner_id' . $owner->id . '" '.$checked.'>' . $owner->name . '</label>';
            }
            return '<fieldset id="jform_owner_id" class="checkboxes">' . $var_list . '</fieldset>';
        }

        return 'Nenhum Proprietário/Usufrutário Cadastrado!';
    }

    protected function getValues($owner_id)
    {
        $form = $this->form;
        $id = $form->getData()->get('id');

        if(!empty($id)){
            $db = JFactory::getDBO();
            $query = "SELECT owner_id FROM #__enterprises WHERE id = $id";
            $db->setQuery($query);
            $enterprises_owners = $db->loadObjectList();

            if(!empty($enterprises_owners)) {
                $array_owner = explode(', ', $enterprises_owners[0]->owner_id);
                $owner_id = (int)$owner_id;
            }

            $checked = '';
            if(!empty($array_owner)){
                foreach ($array_owner as $enterprises_owner) {
                    if($enterprises_owner == $owner_id){
                        $checked = 'checked';
                    }
                }
            }
            return $checked;
        }
        return true;
    }
}
