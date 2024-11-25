<?php


//Класс для обслуживания базы данных
class DataBaseManager

{
    private $conn;
    private $dataBaseName = "";
    //Создание менеджера базы данных
    public function __construct($dbName = "testtask3bd")
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

    // Создание HTML для вывода комментария
    function CommentToHTML($username, $content, $createdAt): string
    {
        // Экранирование данных
        $username = htmlspecialchars($username);
        $content = htmlspecialchars($content);
        $createdAt = htmlspecialchars($createdAt);
    
        return "
            <div class='comment'>
                <div class='comment-header'>
                    <span class='username'>$username</span>
                    <span class='created-at'>$createdAt</span>
                </div>
                <div class='comment-content'>
                    <p>$content</p>
                </div>
            </div>
        ";
    }

    //Создание HTML для вывода всех комментариев
    function ShowAllComments($table): string
    {
        $output = "<div class='comments-container'>";
        $output .= "<h3>Комментарии</h3>";
    
        $data = $this->conn->query("SELECT username, content, created_at FROM $table ORDER BY created_at ASC");
    
        // Обход каждой строки и генерация HTML
        foreach ($data as $row) {
            $output .= $this->CommentToHTML($row['username'], $row['content'], $row['created_at']);
        }
    
        $output .= "</div>";
        return $output;
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

    // Добавление комментария с защитой от sql инъекций
    function AddComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
            $username = htmlspecialchars($_POST['username']);
            $content = htmlspecialchars($_POST['content']);
    
            if (!empty($username) && !empty($content)) {
                $stmt = $this->conn->prepare("INSERT INTO comments (username, content) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $content);
                $stmt->execute();
                $stmt->close();
    
                // Перенаправление на ту же страницу для предотвращения повторной отправки формы
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        }
    }

    //очистка базы данных с комментариями
    function ClearTable($table)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_table'])) {
            $this->conn->query("TRUNCATE TABLE $table");
            
            header("Location: " . $_SERVER['PHP_SELF']);
                exit;
        }
        
    }
}
require_once("queries.php");
$dataBaseManager = new DataBaseManager("testDBtask3");
$dataBaseManager->ExecuteQueries($createDataBaseQuery);
$dataBase = $dataBaseManager->ShowAllComments("comments");
$dataBaseManager->AddComment();
$dataBaseManager->ClearTable("comments");
?>