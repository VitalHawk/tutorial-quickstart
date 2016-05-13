<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Tracy\Debugger;

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
            }
                
            $sel = $this->database->table('books')
                    ->group('books.id')
                    ->select('books.*'
                            . ', publisher.name publisher, category.name category'
                            //. ', GROUP_CONCAT(CONCAT(:BooksAuthors.author.surname, " ", :BooksAuthors.author.name)) author'
                            )
                    //. ', CONCAT(:BooksAuthors.author.surname, " ", :BooksAuthors.author.name) author'
                    //->aggregation('GROUP_CONCAT(CONCAT(:BooksAuthors.author.surname, " ", :BooksAuthors.author.name))')
                    ;

            if ($this->catId) {
                $sel->where('category_id', $this->catId);
            }
            if ($this->authId) {
                $sel->where(':BooksAuthors.author.id', $this->authId);

            }

            if ($this->pubsId) {
                $sel->where('publisher_id', $this->pubsId);
            }

            //Debugger::barDump($sel->getSql());
            $this->template->books = array();
            foreach ($sel as $book) {
                $this->template->books[] = array(
                    'id' => $book->id,
                    'name' => $book->name,
                    'category' => $book->category,
                    'publisher' => $book->publisher,
                    'date' => $book->date,
                    'pages' => $book->pages,
                    'author' => $this->database->table('books')->where('books.id', $book->id)
                        ->aggregation('GROUP_CONCAT(CONCAT(:BooksAuthors.author.surname, " ", :BooksAuthors.author.name))')
                );
            }            

//        }
//        else {
//                $this->template->books = 
//                    $this->database->query('call sp_getAllBooks()')->fetchAll();
//            }
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
                Debugger::barDump($ex);
                Debugger::log($ex);
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
            $form->addSubmit('filter', 'Refresh')->getControlPrototype()->addClass("btn btn-info btn-md"); // ajax");
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
            if ($this->isAjax()) {
                $this->redrawControl('details');
            }
        }
}
