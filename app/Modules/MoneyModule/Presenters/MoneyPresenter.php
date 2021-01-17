<?php
declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\MoneyManager;
use Nette\Application\UI\Form;
use Tomaj\Form\Renderer\BootstrapRenderer;

class MoneyPresenter extends Nette\Application\UI\Presenter
{

    /** @var MoneyManager */
    private $moneyManager;

    /**
     * MoneyPresenter constructor.
     * @param MoneyManager $moneyManager
     */
    public function __construct(MoneyManager $moneyManager)
    {
        $this->moneyManager = $moneyManager;
    }

    /**
     * @throws Nette\Application\AbortException
     */
    public function renderDefault()
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

        // Vypis vseho
        $add = $this->moneyManager->getAddMoney();
        $spend = $this->moneyManager->getSpendMoney();
        $this->template->add = $add;
        $this->template->spend = $spend;

        // Soucet
        $alladd = $this->moneyManager->getAllAddMoney();
        bdump($alladd, 'add');
        $this->template->alladd = $alladd;
        $allspend = $this->moneyManager->getAllSpendMoney();
        bdump($allspend, 'spend');
        $this->template->allspend = $allspend;
        $result = $alladd - $allspend;
        $this->template->result = $result;
//        bdump($this->template->allspend, 'vsechno');

        // Dnes
        $today = $this->moneyManager->selectToday();
        bdump($today, 'dnes');
        $this->template->today = $today;
        $result = $this->moneyManager->selectTodayAll();
        $this->template->resultToday = $result;
        bdump($result, 'vysledek');

        // Tento týden
        $thisWeek = $this->moneyManager->selectThisWeek();
        bdump($thisWeek, 'tento tyden');
        $this->template->thisweek = $thisWeek;
        $thisWeekAll = $this->moneyManager->selectThisWeekAll();
        bdump($thisWeekAll, 'tento tyden all');
        $this->template->thisweekall = $thisWeekAll;
    }

    /**
     * @throws Nette\Application\AbortException
     */
    public function actionAdd()
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

        // Vypis vseho
        $add = $this->moneyManager->getAddMoney();
        $spend = $this->moneyManager->getSpendMoney();
        $this->template->add = $add;
//        $this->template->spend = $spend;

        // Soucet
        $alladd = $this->moneyManager->getAllAddMoney();
        bdump($alladd, 'add');
        $this->template->alladd = $alladd;
        $allspend = $this->moneyManager->getAllSpendMoney();
        bdump($allspend, 'spend');
        $this->template->allspend = $allspend;
        $result = $alladd - $allspend;
        $this->template->result = $result;
    }

    public function actionAddMoney()
    {
    }

    /**
     * @param $addid
     * @throws Nette\Application\AbortException
     * @throws Nette\Application\BadRequestException
     */
    public function actionAddEdit($addid)
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

        $moneyAdd = $this->moneyManager->allAdd($addid);
        bdump($moneyAdd, 'money add tady');
        if (!$moneyAdd) {
            $this->Error('cosi');
        }
        $this['addForm']->setDefaults($moneyAdd->toArray());
    }

    /**
     * @param $addid
     * @throws Nette\Application\AbortException
     */
    public function actionAddDelete($addid)
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

        $this->moneyManager->deleteAdd($addid);
        $this->redirect('Money:add');
        $this->terminate();
    }

    /**
     * @return Form
     */
    public function createComponentAddForm(): Form
    {
        // https://github.com/nextras/form-components
        $form = new Form();
        $form->setRenderer(new BootstrapRenderer);
        $form->addInteger('amount', 'Částka:');
        $form->addText('addTime', 'Datum')
            ->setHtmlType('date');
        $categoryAdd = $this->moneyManager->getAllCategoryAdd();
//        bdump($categoryAdd, 'kategory add');
//        $category = [];
//        foreach ($categoryAdd as $key => $item) {
//           $category[$key] = $item;
//        }
//        bdump($category, 'kategorie v poli');
//        $category = [
//            1 => 'Faktury',
//            2 => 'Ostatní',
//        ];
        $form->addSelect('category', 'Kategorie:', $categoryAdd);
        $form->addTextArea('description', 'Popis');

        $form->addSubmit('ulozit', 'Uložit');
        $form->onSuccess[] = [$this, 'onSuccessdAddForm'];
        return $form;
    }


    /**
     * @param Form $form
     * @param \stdClass $values
     * @throws Nette\Application\AbortException
     * @throws Nette\Application\BadRequestException
     */
    public function onSuccessdAddForm(Form $form, \stdClass $values): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->error('Pro vytvoření, nebo editování příspěvku se musíte přihlásit.');
        }

        $addid = $this->getParameter('addid');
        bdump($addid, 'addid');
        bdump($values, 'values');
        if ($addid) {
            $this->moneyManager->updateAddMoney($values, $addid);
            $this->flashMessage('Bylo to upraveno');
        } else {
            $this->moneyManager->saveAddMoney($values);
            $this->flashMessage('Bylo to uloženo');
        }
        $this->redirect('Money:add');
    }


    /**
     * @throws Nette\Application\AbortException
     */
    public function actionSpend(): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

        $this->template->add = 'pridat';
        // Vypis vseho
        $add = $this->moneyManager->getAddMoney();
        $spend = $this->moneyManager->getSpendMoney();
        $this->template->add = $add;
        $this->template->spend = $spend;

        // Soucet
        $alladd = $this->moneyManager->getAllAddMoney();
        bdump($alladd, 'add');
        $this->template->alladd = $alladd;
        $allspend = $this->moneyManager->getAllSpendMoney();
        bdump($allspend, 'spend');
        $this->template->allspend = $allspend;
        $result = $alladd - $allspend;
        $this->template->result = $result;
//        bdump($this->template->allspend, 'vsechno');
    }


    /**
     *
     */
    public function actionSpendMoney()
    {
    }


    /**
     * @param $spendid
     * @throws Nette\Application\BadRequestException
     */
    public function actionSpendEdit($spendid): void
    {
        $spend = $this->moneyManager->getSpendMoneyWhereId($spendid);
        bdump($spend, 'nactena data');
        if (!$spend) {
            $this->Error('chyba');
        }
//        $this['spendForm']->setDefaults($spend);
        $this['spendForm']->setDefaults($spend->toArray());
    }


    /**
     * @param $spendid
     * @throws Nette\Application\AbortException
     */
    public function actionSpendDelete($spendid)
    {
        $this->moneyManager->deleteSpend($spendid);
        $this->redirect('Money:spend');
        $this->terminate();
    }


    /**
     * @return Form
     */
    public function createComponentSpendForm(): Form
    {
        $datetime = new \DateTime();
        $date = $datetime->format('Y-m-d H:i:s');
        bdump($date, 'cas');
        // https://github.com/nextras/form-components
        $form = new Form();
        $form->setRenderer(new BootstrapRenderer);
        $form->addInteger('amount', 'Částka:');
        $form->addText('spendTime', 'Datum')
//            ->setHtmlAttribute('class', 'form_datetime')
//            ->setHtmlAttribute('id', 'datetimepicker')
//            ->setHtmlAttribute('data-date-format', 'yyyy-mm-dd hh:ii')
            ->setDefaultValue($date);
//            ->setHtmlType('date');
        $category = [
            1 => 'Auto',
            2 => 'Jídlo',
            3 => 'Obědy',
            4 => 'Eda',
            5 => 'Radka',
            6 => 'Nafta',
            7 => 'Domáctnost',
            8 => 'Hospoda',
            9 => 'Stěna',
            10 => 'Zdraví / lékárna',
            11 => 'Cetování',
            12 => 'Ostatní',
            13 => 'Oblečení',
            14 => 'Zděchov',
        ];
        $form->addSelect('category', 'Kategorie:', $category);
        $form->addTextArea('description', 'Popis');

        $form->addSubmit('ulozit', 'Uložit');
        $form->onSuccess[] = [$this, 'onSuccessdSpendForm'];
        return $form;
    }


    /**
     * @param Form $form
     * @param \stdClass $values
     * @throws Nette\Application\AbortException
     */
    public function onSuccessdSpendForm(Form $form, \stdClass $values): void
    {
        bdump($values);
        $spendid = $this->getParameter('spendid');
        if ($spendid) {
            $this->moneyManager->updateSpendMoney($spendid, $values);
            $this->flashMessage('Bylo to upravene.');
        } else {
            $this->moneyManager->spendMoney($values);
            $this->flashMessage('Bylo to uloženo');
        }
        $this->redirect('Money:spend');
    }


    /**
     * @throws Nette\Application\AbortException
     */
    public function actionCategoryAdd()
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }


    /**
     * @return Form
     */
    public function createComponentCategoryAddForm(): Form
    {
        $form = new Form();
        $form->setRenderer(new BootstrapRenderer);
        $form->addText('categoryAdd', 'Název kategorie:');
        $form->addSubmit('save', 'Uložit');
        $form->onSuccess[] = [$this, 'succeededCategoryAddForm'];
        return $form;
    }


    /**
     * @param Form $form
     * @param \stdClass $values
     * @throws Nette\Application\AbortException
     */
    public function succeededCategoryAddForm(Form $form, \stdClass $values): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
        $this->moneyManager->addCategoryAdd($values);
        $this->flashMessage('Ulozeno');
        $this->redirect('this');
    }


}