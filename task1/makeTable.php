<?php
class ScoreTableMaker
{
    private $scores = [];
    private $subjects = [];

    //сортировка учеников и предметов
    private function SortData(): void
    {
        ksort($this->scores);
        ksort($this->subjects);
    }

    //проверка на правильный формат данных
    private function ValidateData($data)
    {
        if (
            count($data) !== 3 ||
            !is_string($data[0]) ||
            !is_string($data[1]) ||
            !is_numeric($data[2])
        ) {
            throw new InvalidArgumentException("Invalid data");
        }
    }

    //Подсчет баллов по каждому ученику по каждому предмету
    private function AggregateScores($data): void
    {
        foreach ($data as $row) {
            list($student, $subject, $score) = $row;
            $this->ValidateData($row);
            if (!isset($this->scores[$student][$subject])) {
                $this->scores[$student][$subject] = 0;
            }
            $this->scores[$student][$subject] += $score;
            //запись предметов в массив, для экономии памяти используются только ключи
            $this->subjects[$subject] = "";
        }

        $this->SortData();
    }

    //Подготовка HTML таблицы
    private function MakeHTMLTable(): string
    {
        $resultTable = "<table border='1'><tr><th></th>";

        //перебор всех ключей списка предметов для создания заголовков предметов
        foreach ($this->subjects as $subject => $unused) {
            $resultTable .=  "<th>" . htmlspecialchars($subject) . "</th>";
        }
        $resultTable .=  "</tr>";

        // заполнение таблицы фамилиями учеников и баллами
        foreach ($this->scores as $student => $studentScores) {
            $resultTable .=  "<tr><th>" . htmlspecialchars($student) . "</th>";
            foreach ($this->subjects as $subject => $unused) {
                $resultTable .=  "<td>" . ($studentScores[$subject] ?? '') . "</td>";
            }
            $resultTable .=  "</tr>";
        }

        $resultTable .= "</table>";
        return $resultTable;
    }

    // Создание таблицы баллов 
    public function MakeTable($data): string
    {
        $this->AggregateScores($data);
        $result =   $this->MakeHTMLTable();
        return $result;
    }
}


$data = [
    ['Иванов', 'Математика', 5],
    ['Иванов', 'Математика', 4],
    ['Иванов', 'Математика', 5],
    ['Петров', 'Математика', 5],
    ['Сидоров', 'Физика', 4],
    ['Иванов', 'Физика', 4],
    ['Петров', 'ОБЖ', 4],
];

$tableMaker = new ScoreTableMaker();
$resultTable = $tableMaker->MakeTable($data);

echo $resultTable;
