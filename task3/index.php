<?php
require_once("dataBaseManager.php");
?>

<head>
    <title>Комментарии</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <!-- Форма добавления нового комментария -->
        <form action="" method="POST" class="comment-form">
            <h3>Добавить комментарий</h3>
            <div class="form-group">
                <label for="username">Ваше имя:</label>
                <input type="text" id="username" name="username" maxlength="50" required>
            </div>
            <div class="form-group">
                <label for="content">Комментарий:</label>
                <textarea id="content" name="content" rows="4" maxlength="500" required></textarea>
            </div>
            <button type="submit" name="add_comment">Отправить</button>

        </form>

        <!-- Кнопка очистки таблицы -->
        <form action="" method="POST" class="clear-table-form">
            <button type="submit" name="clear_table" class="clear-btn">Очистить комментарии</button>
        </form>
        <div class="section">
            <!-- Вывод комментариев -->
            <?php echo $dataBase; ?>
        </div>
    </div>
</body>

</html>