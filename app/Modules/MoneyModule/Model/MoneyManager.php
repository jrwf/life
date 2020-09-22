<?php
declare(strict_types=1);

namespace App;

use mysql_xdevapi\DatabaseObject;
use Nette;

class MoneyManager
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function saveAddMoney($values)
    {
        $this->database->query('insert into moneyAdd', [
            'amount' => $values->amount,
            'category' => $values->category,
            'description' => $values->description,
            'addTime' => new \DateTime($values->addTime),
        ]);
    }

    public function updateAddMoney($values, $addid)
    {
//        $this->database->table('moneyAdd')->insert($values);
        $this->database->query('update moneyAdd set', [
            'amount' => $values->amount,
            'category' => $values->category,
            'description' => $values->description,
            'addTime' => new \DateTime($values->addTime),
        ], 'where moneyId = ?', $addid);
    }

    public function allAdd($addid)
    {
        return $this->database->table('moneyAdd')->get($addid);
    }

    public function deleteAdd($addid)
    {
        $this->database->table('moneyAdd')
            ->where('moneyId', $addid)
            ->delete();
    }

    public function spendMoney($values)
    {
        $this->database->query('insert into moneySpend', [
            'amount' => $values->amount,
            'category' => $values->category,
            'description' => $values->description,
            'spendTime' => new \DateTime($values->spendTime),
        ]);
    }

    public function getAddMoney()
    {
        return $this->database->query('select 
                                            moneyId, 
                                            addTime, 
                                            category, 
                                            description, 
                                            amount,
                                            (select categoryAdd from categoryAdd where categoryAddId = category) as categoryName
                                            -- sum(amount) as celkem
                                            from moneyAdd order by addTime')->fetchAll();
    }

    /*
     * Spend money
     */
    public function getSpendMoney()
    {
        return $this->database->query('select 
                                            moneyId, 
                                            spendTime, 
                                            category, 
                                            description, 
                                            amount
                                            from moneySpend order by spendTime')->fetchAll();
    }

    public function updateSpendMoney($spendid, $values)
    {
        $this->database->table('moneySpend')
        ->where('moneyId', $spendid)
            ->update([
                'amount' => $values->amount,
                'category' => $values->category,
                'description' => $values->description,
                'spendTime' => new \DateTime($values->spendTime),
            ]);
    }

    public function getAllSpendMoney()
    {
        return $this->database->query('select sum(amount) as vsechno from moneySpend')->fetchField();
    }

    public function getSpendMoneyWhereId($spendid)
    {
        return $this->database->table('moneySpend')->get($spendid);
    }

    public function getAllAddMoney()
    {
        return $this->database->query('select sum(amount) as vsechno from moneyAdd')->fetchField();
    }

    public function deleteSpend($spendid)
    {
        $this->database->table('moneySpend')
            ->where('moneyId', $spendid)
            ->delete();
    }

    public function selectToday()
    {
        return $this->database->query('select * from moneySpend where date_format(spendTime,  "%Y-%m-%d") = current_date()')->fetchAll();
    }

    public function selectTodayAll()
    {
        return $this->database->query('select sum(amount) as result from moneySpend where date_format(spendTime,  "%Y-%m-%d") = current_date()')->fetch();
    }

    public function selectThisWeek()
    {
        return $this->database->query('select * from moneySpend where WEEK(spendTime, 5) = WEEK(CURDATE()) order by spendTime asc')->fetchAll();
//        return $this->database->query('select * from moneySpend where WEEK(CURDATE()) order by spendTime asc')->fetchAll();
//        return $this->database->query('select * from moneySpend where WEEK(CURDATE()) group by spendTime')->fetchAll();
    }

    public function selectThisWeekAll()
    {
        return $this->database->query('select sum(amount) as resultWeek from moneySpend where WEEK(spendTime, 5) = WEEK(CURDATE())')->fetch();
//        return $this->database->query('select sum(amount) as resultWeek from moneySpend where WEEK(CURDATE())')->fetch();
    }

    /*
     *
     * Category
     */

    public function addCategoryAdd($values)
    {
        $this->database->query('INSERT INTO categoryAdd', [
            'categoryAdd' => $values->categoryAdd,
        ]);
    }

    public function addCategorySpend($values)
    {
        $this->database->query('INSERT INTO categorySpend', [
            'categorySpend' => $values->categorySpend,
        ]);
    }

    public function getAllCategoryAdd()
    {
        return $this->database->query('SELECT categoryAddId, categoryAdd FROM categoryAdd')->fetchPairs();
    }
}
