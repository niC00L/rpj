<?php

namespace App\AdminModule\Components\Forms\GridForm;

use Nette\Utils\Callback;
use Nette\Application\UI\Form,
    Nette\Utils\Strings;

class gridFormControl extends \Nextras\Datagrid\Datagrid {

    public $database;
    public $global;

    public function __construct(\Nette\Database\Context $database, \App\Model\GlobalSettings $global) {
        parent::__construct();
        $this->database = $database;
        $this->global = $global;
    }

    public $table;
    public $columns;
    public $filter;
    
    
    public function render($template = 'gridFormDefault') {
        $this->template->setFile(__DIR__ . '/' . $template . '.latte');
        $this->renderTemplate();
    }

    public function setForms($table, $columns, $filter) {
        $this->table = $table;
        $this->columns = $columns;
        $this->filter = array($filter);
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
                $grid->addColumn($field[0], $field[1]);
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

        $grid->addCellsTemplate(__DIR__ . '/grid.columns.latte');

        $grid->setEditFormFactory(function($row) {
            $form = new \Nette\Forms\Container;
            $roles = array(
                'banned' => 'Ban',
                'user' => 'Používateľ',
                'editor' => 'Editor',
                'admin' => 'Admin',
            );
            $form->addSelect('role', 'Rola', $roles)
                    ->setAttribute('class', 'browser-default');
            !$row ? : $form->setDefaults($row);

            $form->addSubmit('save', 'Uložiť')->getControlPrototype()->class = 'btn';
            $form->addSubmit('cancel', 'X')->getControlPrototype()->class = 'btn';

            return $form;
        });

        $grid->setDeleteCallback($this->deleteRow);

        $grid->setEditFormCallback(function($data) {
            $this->flashMessage('Saving data: ' . json_encode($data->getValues()));
            $this->invalidateControl('flashes');
        });

        return $grid;
    }

    public function deleteRow($primary) {
        dump($primary);
    }
}
