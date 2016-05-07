<?php

namespace App\Presenters;

use Nette;
use App\Forms\SignFormFactory;
use Tracy\Debugger;


class SignPresenter extends BasePresenter
{
	/** @var SignFormFactory @inject */
	public $factory;

	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = $this->factory->create();
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Homepage:');
		};
		return $form;
	}


	public function actionOut()
	{
            if ($this->getUser()->isLoggedIn()) {
		$this->getUser()->logout();
		//$this->flashMessage('You have been signed out.');
            }
            if ($this->isAjax()) {
                $this->redrawControl('login');
            }
	}
        
        public function handleForm()
        {
            if ($this->isAjax()) {
                $this->redrawControl('login');
            }
        }
        
        public function actionLogin() {
            $post = $this->getHttpRequest()->getPost();
            if ($post) {
                try {
                    $this->getUser()->login($post['login'], $post['password']);
                }
                catch(\Exception $ex) {
                    Debugger::log($ex);
                }

                if ($this->isAjax()) {
                    $this->redrawControl('login');
                }
            }
        }
        
        public function actionUpdate()
        {
            if ($this->isAjax()) {
                $this->redrawControl('script');
            }
        }
}
