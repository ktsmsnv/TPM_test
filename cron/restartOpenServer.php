<?php
// Путь к файлу управления Open Server Panel
$openServerControl = 'C:\\OSPanel\\Open Server Panel.exe';

// Команда для перезагрузки Open Server Panel
$command = '"' . $openServerControl . '" /restart';

// Выполнение команды
exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    echo "Ошибка при перезагрузке Open Server Panel.";
} else {
    echo "Open Server Panel успешно перезагружен.";
}
?>