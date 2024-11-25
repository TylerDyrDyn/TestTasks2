<?php

class AliceBrothersAndSisters
{
    //Проверка на то что есть как минимум один брат, для которого вывводится количество сестер, а так же что количество сестер не отрицательное
    private function ValidateData($brothers,$sisters)
    {
        if (!is_int($brothers) || !is_int($sisters)) {
            throw new InvalidArgumentException('Brothers and sisters count must be integer');
        }
        
        if ($brothers <= 0) {
            throw new InvalidArgumentException('Number of brothers must be positive');
        }
        
        if ($sisters < 0) {
            throw new InvalidArgumentException('Number of sisters must be not negative');
        }
    }





    
   

    public function Action():int
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sisters']) && isset($_POST['brothers'])) {
            $brothers = (int)$_POST['brothers'];
            $sisters = (int)$_POST['sisters'];
            $countOfSister=$this->GetAmountOfSisterOfAlicesBrother($brothers,$sisters);
            echo'Итого сестер '.$countOfSister.'<br><br>';
            return $countOfSister;
        }
        return -1;
    }

    //Получение количество сестер для произвольного брата Алисы
    public function GetAmountOfSisterOfAlicesBrother($brothers, $sisters): int
    {
        
        $this->ValidateData($brothers, $sisters);
        //У любого брата Алисы есть как минимум одна сестра - Алиса
        $Alice = 1;
        return $sisters + $Alice;
        }
    }


$SistersAndBrothers= new AliceBrothersAndSisters();

$countOfSister=$SistersAndBrothers->Action();

?>
<form action="#" method="post">
    <label for="sisters">Сестры:</label>
    <input name="sisters" id="sisters" type="number">
    <label for="brothers">Братья:</label>
    <input name="brothers" id="brothers" type="number">

    <button type="submit">Отправить</button>
</form>