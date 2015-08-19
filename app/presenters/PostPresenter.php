<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class PostPresenter extends BasePresenter {

    public function renderShow($address) {
        $this->template->setFile(__DIR__ . '/templates/Post/showPost.latte');

        $post = $this->template->post = $this->database->table('page')->where('address', $address)->fetch();
        $this['postForm']->setDefaults($post->toArray());
    }

    public function renderCategory($address) {
        $this->template->setFile(__DIR__ . '/templates/Post/showCategory.latte');

        $category = $this->template->category = $this->database->table('page_ctg')->where('address', $address)->fetch();
        $ctg_id = $this->database->table('page_ctg')->where('address', $address)->fetch()->id;

        $page_id = $this->database->table('page_ctg_sort')->where('ctg_id', $ctg_id)->select('page_id');
        $posts = $this->template->posts = $this->database->table('page')->where('id', $page_id)->fetchAll();

        $this['postForm']->setDefaults($category->toArray());
    }

    protected function createComponentPostForm() {

//	ak sa zobrazuje clanok vyberie sa ktore checkboxy maju byt zaskrtnute
        if ($this->getAction() == 'show') {
            $address = $this->getParameter('address');
            $id = $this->database->table('page')->where('address', $address)->fetch()->id;            

            $ctgs_in = $this->database->table('page_ctg_sort')->where('page_id', $id);            
        }
        
//      vyberu sa vsetky checkboxy
        $ctgs = $this->database->table('page_ctg')->fetchAll();

//        vytvori sa formular
        $form = new Form;
        $form->addText('address', 'Adresa:')
                ->setRequired();
        $form->addText('title', 'Titulok:')
                ->setRequired();
        $form->addTextArea('description', 'Popis:')
                ->setAttribute('class', 'materialize-textarea');

        if ($this->getAction() == 'show' || $this->getAction() == 'createPost') {
            $form->addTextArea('text', 'Obsah:')
                    ->setAttribute('class', 'materialize-textarea');

//	vykreslia sa checkboxy
            foreach ($ctgs as $ctg) {
                $form->addCheckbox('category_' . $ctg['id'], $ctg['title']);
            }
        }

//	pouzitym sa prida hodnota true
        if ($this->getAction() == 'show') {
            foreach ($ctgs_in as $ctg_in) {
                $form->setValues(['category_' . $ctg_in->ctg_id => true]);
            }
        }

        $form->addSubmit('send', 'Uložit a publikovat')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'postFormSucceeded');
        return $form;
    }

    public function postFormSucceeded($form, $values) {
        $address = $this->getParameter('address');

//	Vyradi checkboxy z values aby sa nezapisovali do tabulky pages
        $ctg_sort = array();
        foreach ($values as $key => $value) {
            if (Nette\Utils\Strings::startsWith($key, 'category_')) {
//				ak je checkbox zaskrtnuty vlozi jeho hodnotu do pola $ctg_sort
                if ($values[$key] == true) {
//				do pola sa vlozia len cisla
                    array_push($ctg_sort, substr($key, -1));
                }
                unset($values[$key]);
            }
        }


        if ($address) {
            if ($this->getAction() == 'show') {
                $table = 'page';
            } elseif ($this->getAction() == 'category') {
                $table = 'page_ctg';
            }

            $id = $this->database->table($table)->where('address', $address)->fetch()->id;

//	zapisanie/pridanie obsahu stranky
            $post = $this->database->table($table)->get($id);
            $post->update($values);
        } else {
            if ($this->getAction() == 'createPost') {
                $table = 'page';
            } elseif ($this->getAction() == 'createCategory') {
                $table = 'page_ctg';
            }
            $post = $this->database->table($table)->insert($values);
        }

//	zapisanie clankov v kategoriach do spolocnej tabulky
        if ($this->getAction() == 'show') {
            $this->database->table('page_ctg_sort')->where('page_id', $id)->delete();
        }                
        
        foreach ($ctg_sort as $ctg_id) {
            $this->database->table('page_ctg_sort')->insert(array(
                'page_id' => $post['id'],
                'ctg_id' => $ctg_id
            ));
        }


        $this->flashMessage('Uspesne publikovane.', 'success');
        $this->redirect('this', ['address' => $values['address']]);
    }

}
