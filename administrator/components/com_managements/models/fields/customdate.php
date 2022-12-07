<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('calendar');

class JFormFieldCustomDate extends JFormFieldCalendar
{
    public $type = 'CustomCalendar';

    protected $defaultFormat = 'd-m-Y';

    /*
     * Un jour d'intervalle entre le debut et la fin
     */
    protected $interval = 'P1D';

    protected function getInput()
    {
        parent::getInput();

        // Build the attributes array.
        $attributes = array();

        empty($this->size)      ? null : $attributes['size'] = $this->size;
        empty($this->maxlength) ? null : $attributes['maxlength'] = $this->maxlength;
        empty($this->class)     ? null : $attributes['class'] = $this->class;
        !$this->readonly        ? null : $attributes['readonly'] = '';
        !$this->disabled        ? null : $attributes['disabled'] = '';
        empty($this->onchange)  ? null : $attributes['onchange'] = $this->onchange;
        empty($hint)            ? null : $attributes['placeholder'] = $hint;
        $this->autocomplete     ? null : $attributes['autocomplete'] = 'off';
        !$this->autofocus       ? null : $attributes['autofocus'] = '';

        if ($this->required) {
            $attributes['required'] = '';
            $attributes['aria-required'] = 'true';
        }

        $date = new DateTime("now");

        $format = $this->element['format'] ? (string) $this->element['format'] : $this->defaultFormat;
        $validFormat = preg_replace('/%/', '', $format);

        if ($this->element['default'] == 'start') {
            $this->value = $date->format($validFormat);
        } else if ($this->element['default'] == 'end') {
            $date->add(new DateInterval($this->interval));

            $this->value = $date->format($validFormat);
        }

        return JHtml::_('calendar', $this->value, $this->name, $this->id, $format, $attributes);
    }
}
?>