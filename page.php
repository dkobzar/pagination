<!--написать сайт словарь, где пользователь вводит слово и его перевод. после добавления слова
отображаются в виде таблицы. на страницу выводить по 20 слов. сделать пагинацию.
добавить возможность скрывать левую или правую часть слов-->

<!--комментарий-->
<form method="post">
    <input type="text" name="word" value="Enter your word"/>
    <input type="text" name="translate" value="Enter translate"/>
    <input type="submit" value="ADD">
</form>
<?php

//заполнение файла word.db
if (!empty($_POST['word']) &&
    !empty($_POST['translate'])
) {
    //открываем файл word.db для чтения и записи, указатель помещаем в конец файла. если его нет - файл создается
    $db = fopen("word.db", "a+");

    //берем данные из двух input и пишем в открытый файл. функция передает значения из массива
    fputcsv($db, [
        $_POST['word'],
        $_POST['translate']
    ]);

    //закрываем файл word.db
    fclose($db);
}

//получаем количество строк в файле. в конце цикла в $stringCount будет записано количество строк
$db = fopen("word.db", "r");
$stringCount = 0;
while (!feof($db)) {
    fgets($db);
    $stringCount++;
}
fclose($db);

//получаем количество страниц, которое должно быть сформировано
$maxRows = 20; //количество строк, которые нам необходимо вывести
$pageCount = ceil($stringCount / $maxRows);
$pageNumber = empty($_GET['page']) || intval($_GET['page']) < 1 ? 1 : intval($_GET['page']);
$firstRow = $maxRows * ($pageNumber - 1);
$lastRow = $maxRows + $firstRow;
$counter = 0;
if (($db = fopen("word.db", "r")) !== false) {
    echo "<table>";
    while (($words = fgetcsv($db, 1000, ",")) !== false) {
        if ($firstRow <= $counter && $counter < $lastRow) {
            echo "<tr>" . "<td>" . $words[0] . "</td>" . "<td>" . $words[1] . "</td>" . "</tr>";
        }
        $counter++;
    }
    echo "</table>";
    fclose($db);
}
echo '<a href="?page=' . ($pageNumber - 1) . '">Назад >></a>';
for ($i = 1; $i <= $pageCount; $i++){
    echo '<a href="?page=' . $i . '">' . $i . '</a> ';
}
echo '<a href="?page=' . ($pageNumber + 1) . '">Вперед >></a>';
