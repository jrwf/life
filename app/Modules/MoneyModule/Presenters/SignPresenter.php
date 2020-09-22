<?php
declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


class SignPresenter extends Nette\Application\UI\Presenter
{
    /**
     * @return Form
     */
    protected function createComponentSignInForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Uživatelské jméno:')
            ->setRequired('Prosím vyplňte své uživatelské jméno.');

        $form->addPassword('password', 'Heslo:')
            ->setRequired('Prosím vyplňte své heslo.');

        $form->addSubmit('send', 'Přihlásit');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }


    /**
     * @param Form $form
     * @param \stdClass $values
     * @throws Nette\Application\AbortException
     */
    public function signInFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            $this->getUser()->login($values->username, $values->password);
            $this->redirect('Homepage:');

        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }


    /**
     * @throws Nette\Application\AbortException
     */
    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.');
        $this->redirect('Homepage:');
    }
}
