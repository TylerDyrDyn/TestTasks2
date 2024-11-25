<?php
//Подключение файлов
require_once("queries.php");
require_once("dataBaseManager.php");
$DBName = "task2bd";

$dbManger = new DataBaseManager( $DBName);

//Создание базы данных
$queries = $createDataBaseQuery;
$dbManger->ExecuteQueries($queries);
$dataBaseBefore = $dbManger->DataBaseToHTML($DBName);

//Очистка базы данных 
$queries = $cleanDataQuery;
$dbManger->ExecuteQueries($queries);
$dataBaseAfter = $dbManger->DataBaseToHTML($DBName);

$dbManger->Disconnect();
?>

<head>
    <title>База данных до и после очистки</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="section">
            <h2>До очитски</h2>
            <?php echo $dataBaseBefore ?>
        </div>

        <div class="section">
            <h2>После очитски</h2>
            <?php echo $dataBaseAfter ?>
        </div>
    </div>
</body>

</html>