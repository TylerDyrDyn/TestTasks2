<?php


//Класс для обслуживания базы данных
class DataBaseManager

{
    //Ассоциативный массив необходимый для того что бы представить имена таблиц и столбцов в читаемом виде
    private $russianNames = [
        "categories" => "Категории",
        "products" => "Товары",
        "stocks" => "Склады",
        "availabilities" => "Наличие",
        "id" => "ID",
        "amount" => "Количество",
        "product_id" => "ID Товара",
        "stock_id" => "ID Склада",
        "title" => "Название",
        "category_id" => "ID Категории"
    ];
    private $conn;
    private $dataBaseName = "";
    public function __construct($dbName = "testtask2bd")
    {
        $this->dataBaseName = $dbName;
        $this->Prepare();
    }
    
    private function Connect()
    {
        $this->conn = new mysqli('localhost', 'root', '');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function CreateDataBase()
    {
        if ($this->conn->query("CREATE DATABASE IF NOT EXISTS $this->dataBaseName") === FALSE) {
            die("Error creating database: " . $this->conn->error);
        }
    }

    //Первоначальное создание и подключение к базе даннхы
    function Prepare()
    {
        $this->Connect();
        $this->CreateDataBase();
        $this->conn->select_db("$this->dataBaseName");
    }

    function Disconnect()
    {
        $this->conn->close();
    }

    //Создание HTML-таблицы на основе таблицы базы данных
    function TableToHTML($table): string
    {
        $resultTable = "";
        $tableName = $this->russianNames[$table] ?? $table;
        $resultTable .= "<h3>" . htmlspecialchars($tableName);
        $columnNames = $this->conn->query("SHOW COLUMNS FROM $table");
        $data = $this->conn->query("SELECT * FROM $table");
        $resultTable .= "<table><tr>";

        //Создание заголовков
        foreach ($columnNames as $columnName) {
            $columnNameToPaste = htmlspecialchars($this->russianNames[$columnName["Field"]] ?? $columnName["Field"]);
            $resultTable .= '<th>' . $columnNameToPaste . "</th>";
        }
        $resultTable .= "</tr>";

        // Заполнение таблиц
        foreach ($data as $row) {
            $resultTable .= "<tr>";
            foreach ($columnNames as $columnName) {
                $columnNameToPaste = $columnName["Field"];
                $resultTable .= "<td>" . htmlspecialchars($row["$columnNameToPaste"]) . "</td>";
            }
            $resultTable .= "</tr>";
        }
        
        $resultTable .= "</table>";
        return $resultTable;
    }

    //Создание всех таблиц в виде HTML из базы данных
    function DataBaseToHTML($dataBaseName): string
    {
        $result = "";
        $allTables = $this->conn->query("SHOW TABLES");
        foreach ($allTables as $table) {
            $tableName = $table["Tables_in_$dataBaseName"];
            $result .= $this->TableToHTML($tableName);
        }
        return $result;
    }

    //Выполнение больших SQL запросов
    function ExecuteQueries($queries): void
    {
        foreach ($queries as $query) {
            if ($this->conn->query($query) === FALSE) {
                die("Error executing query: " . $this->conn->error . "<br>Query: " . $query);
            }
        }
    }
}
