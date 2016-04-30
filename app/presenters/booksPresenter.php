<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class BooksPresenter extends BasePresenter
{
	/** @var Nette\Database\Context */
	private $database;

        private $catId = null, $authId = null, $pubsId = null, $test;
        private $filter = null;

        public function __construct(Nette\Database\Context $database) {
            $this->database = $database;
            if (!defined('DIR_INC')) {
                define('DIR_INC', str_replace('\\', '/', __DIR__) . '/inc/');
            }
	}

        private function initTemplate() {
            if ($this->filter) {
                //var_dump($this->filter);
                $this->catId = $this->filter["category"];
                $this->authId = $this->filter["author"];
                $this->template->test = 'YYYYYY';
                $this->pubsId = $this->filter["publisher"];
                
                $sel = $this->database->table('books');
                
                if ($this->catId) {
                    $sel = $sel->where('category_id', $this->catId);
                }
                if ($this->authId) {
                    $sel = $sel->where(':BooksAuthors.author_id', $this->authId);
                    
                }
                
                if ($this->pubsId)
                {
                    $sel = $sel->where('publisher_id', $this->pubsId);
                }
                
                $books = array();
                foreach ($sel as $book) {
//                    $b = new Nette\Utils\ObjectMixin();
//                    $b->publisher = $book->ref('Publishers', 'publisher_id')->name;
                    //$book->publisher = 
                    \Tracy\Debugger::barDump($book->ref('Publishers', 'publisher_id')->name);
                    $books[] = $book;
                }
                //$this->template->books = $sel->fetchAll();
                $this->template->books = $books;
                \Tracy\Debugger::barDump($this->template->books);
                
            } else {
                $this->template->books = 
                    $this->database->query('call sp_getAllBooks()')->fetchAll();
            }
        }
        
	public function renderList() {
            try {
                require_once DIR_INC . 'tester.php';

                if (!defined('__ROOT__')) {
                    define('__ROOT__', dirname(__FILE__) . '/');
                }

                require_once DIR_INC . 'conf.php';
                require_once DIR_INC . 'user.php';
                require_once DIR_INC . 'router.php';

//                $this->getHttpResponse()->setCookie("uri", $_SERVER['REQUEST_URI'], time()+5);

                $this->initTemplate();
                
            }
            catch(Exception $ex) {
                echo 'Error!!!' . $ex;
            }
        }

        protected function createComponentFilterBooks() {
            $form = new Form;
            
/*            $form->addSelect('category', 'Categories: ', 
                    $this->database->table('Categories')->fetchPairs('id', 'name'))
                        ->setPrompt('-- Select category --')
                        ->setValue($this->catId);
            
            $form->addSelect('author', 'Authors: ', 
                    $this->database->table('Authors')
                        ->select("id, Concat(surname, ' ', name) name")
                        ->fetchPairs('id', 'name'))
                            ->setPrompt('-- Select author --')
                            ->setValue($this->authId);
 */           
            $form->addSelect('category', '', 
                    $this->database->table('Categories')->fetchPairs('id', 'name'))
                        ->setPrompt('-- Select category --')
                        ->setValue($this->catId);
            
            $form->addSelect('author', '', 
                    $this->database->table('Authors')
                        ->select("id, Concat(surname, ' ', name) name")
                        ->fetchPairs('id', 'name'))
                            ->setPrompt('-- Select author --')
                            ->setValue($this->authId);
            
            $form->addSelect('publisher', '', 
                    $this->database->table('Publishers')
                        ->fetchPairs('id', 'name'))
                            ->setPrompt('-- Select publisher --')
                            ->setValue($this->pubsId);
            // $form->setMethod('post');
            $form->addSubmit('filter', 'Refresh')->getControlPrototype()->addClass("btn btn-info btn-md");
//          $form->addSubmit('filter', 'Refresh')
//                    ->setAttribute('class', 'btn btn-info btn-md');
                    
            
            $form->onSuccess[] = array($this, 'filterBooksSucceeded');
            $form->addGroup();
            
            $renderer = $form->getRenderer();
            $renderer->wrappers['controls']['container'] = 'span';
            $renderer->wrappers['label']['container'] = 'span';
            $renderer->wrappers['control']['container'] = 'span';
            $renderer->wrappers['pair']['container'] = NULL;
            
//            foreach($form->getControls() as $key => $value)
//            {
//                if ($value instanceof Nette\Forms\Controls\SubmitButton)
//                    $value->getControlPrototype()->addClass("btn btn-info btn-md");
//            }
            
            
            return $form;
        }
        
        public function filterBooksSucceeded($form, $values) {
            $this->filter = $values;
        }
}
