<?php

namespace App\AdminModule\Components\Forms\GridForm;

use Nette\Application\UI\Form,
    Nette\Utils\Strings;

class gridFormControl extends \App\AdminModule\Components\baseControl {

    public $table;
    public $columns;
    public $filter;

    public function render($template = 'gridFormDefault') {
        $this->template->setFile(__DIR__ . '/' . $template . '.latte');
        $this->template->render();
    }

    public function setForms($table, $columns, $filter) {
        $this->table = $table;
        $this->columns = $columns;
        $this->filter = $filter;
    }

    protected function createComponentGridForm() {
        $grid = new \Nextras\Datagrid\Datagrid;

        if (!$this->columns) {
            $fields = $this->database->query('EXPLAIN ' . $this->table);

            foreach ($fields as $field) {
                $grid->addColumn($field['Field']);
            }
        } else {
            foreach ($this->columns as $field) {
                $grid->addColumn($field);
            }
        }

        $grid->setDatasourceCallback(function($filter, $order) {
            if (!$filter) {
                $filter = $this->filter;
                $selection = $this->database->table($this->table)->where($filter);
            } else {
                $filters = array();
                foreach ($filter as $k => $v) {
                    if ($k == 'id' || is_array($v))
                        $filters[$k] = $v;
                    else
                        $filters[$k . ' LIKE ?'] = "%$v%";
                }
                $selection = $this->database->table($this->table)->where($filters);
            }
            
            if ($order[0])
                $selection->order(implode(' ', $order));

            return $selection;
        });

        $grid->setEditFormFactory(function($row) {
            $form = new Nette\Forms\Container;
//            $form->addDateTimePicker('created_time');
            $form->addText('Display_name')
                    ->setRequired();
            // set your own conditions
            // set your own fileds, inputs
            // these buttons are not compulsory
            $form->addSubmit('save', 'Save data')->getControlPrototype()->class = 'btn';
            $form->addSubmit('cancel', 'Cancel editing')->getControlPrototype()->class = 'btn';

            if ($row) {
                $form->setDefaults($row);
            }
            return $form;
        });

        return $grid;
    }

}
