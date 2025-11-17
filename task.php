
<?php
mb_internal_encoding("UTF-8");

$folderForJS = __DIR__ . '/data/JS';
$fileForJS = $folderForJS . '/tasks.js';
if (!is_dir($folderForJS)) {
    mkdir($folderForJS, 0777, true);
}
if (!file_exists($fileForJS)) {
    file_put_contents($fileForJS, json_encode([], JSON_PRETTY_PRINT));
}
function loadTasks($file)
{
    if (file_exists($file)) {
        $row = file_get_contents($file);
        return json_decode($row, true) ?: [];
    }
    return [];
}

function saveTasks($file, $tasks)
{
    file_put_contents($file, json_encode($tasks, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
function nextId($tasks)
{

    $maxID = 0;
    foreach ($tasks as $task) {
        if ($task['id'] > $maxID) {
            $maxID = $task['id'];
        }
    }
    return $maxID + 1;
}
function findIndexById($tasks, $id)
{
    foreach ($tasks as $index => $task) {
        if ($task['id'] == $id) {
            return $index;
        }
    }
    return -1;
}
function printTask($task)
{
    echo str_pad($task['id'], 6, ' ', STR_PAD_LEFT) . " | " .
        str_pad($task['title'], 25, ' ', STR_PAD_RIGHT) . " | " .
        str_pad($task['status'], 15, ' ', STR_PAD_RIGHT) . " | " .
        str_pad($task['created_at'], 20, ' ', STR_PAD_RIGHT) . " | " .
        str_pad($task['updated_at'], 20, ' ', STR_PAD_RIGHT) . "\n";
}

$action = $argv[1];
$allowedActions = ['add', 'list', 'update', 'delete', 'show'];
$allowedStatus = ['todo', 'in_progress', 'completed'];
if (!in_array($action, $allowedActions)) {
    echo "Invalid action\n";
    exit(1);
}
switch ($action) {
    case 'add':
        $title = $argv[2] ?? null;
        if (empty($title)) {
            echo "Title is required\n";
            exit(1);
        }
        $status = $argv[3] ?? 'todo';
        if (!in_array($status, $allowedStatus)) {
            echo "Invalid status\n";
            exit(1);
        }
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $task = [
            'id' => nextId(loadTasks($fileForJS)),
            'title' => $title,
            'status' => $status,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
        saveTasks($fileForJS, array_merge(loadTasks($fileForJS), [$task]));
        echo "Task added successfully\n";
        break;
    case 'list':
        $filter = $argv[2] ?? null;

        if (empty($filter)) {
            echo "Filter is required (todo / in_progress / completed / all)\n";
            exit(1);
        }
        $allowedStatus = ['todo', 'in_progress', 'completed', 'all'];
        if (!in_array($filter, $allowedStatus)) {
            echo "Invalid status. Allowed: todo, in_progress, completed, all\n";
            exit(1);
        }
        $tasks = loadTasks($fileForJS);
        if ($filter === 'all' || $filter === null) {
            foreach ($tasks as $task) {
                printTask($task);
            }
            exit(0);
        }
        foreach ($tasks as $task) {
            if ($task['status'] === $filter) {
                printTask($task);
            }
        }
        exit(0);
        break;

    case 'update':
        $id = $argv[2] ?? null;
        $tasks = loadTasks($fileForJS);
        if (empty($id)) {
            echo "ID is required\n";
            exit(1);
        }
        $index = findIndexById($tasks, $id);
        if ($index == -1) {
            echo "Task not found\n";
            exit(1);
        }
        $title = $argv[3] ?? null;
        if (empty($title)) {
            echo "Title is required\n";
            exit(1);
        }
        $status = $argv[4] ?? 'todo';
        if (!in_array($status, $allowedStatus)) {
            echo "Invalid status\n";
            exit(1);
        }
        $updated_at = date('Y-m-d H:i:s');
        $tasks[$index]['title'] = $title;
        $tasks[$index]['status'] = $status;
        $tasks[$index]['updated_at'] = $updated_at;
        saveTasks($fileForJS, $tasks);
        echo "Task updated successfully\n";
        break;
    case 'delete':
        $id = $argv[2] ?? null;
        $tasks = loadTasks($fileForJS);

        if (empty($id)) {
            echo "ID is required\n";
            exit(1);
        }

        $index = findIndexById($tasks, $id);

        if ($index == -1) {
            echo "Task not found\n";
            exit(1);
        }
        array_splice($tasks, $index, 1);
        saveTasks($fileForJS, $tasks);
        echo "Task deleted successfully\n";
        break;
    case 'show':
        $id = $argv[2] ?? null;
        $tasks = loadTasks($fileForJS);
        if (empty($id)) {
            echo "ID is required\n";
            exit(1);
        }
        $index = findIndexById($tasks, $id);
        if ($index == -1) {
            echo "Task not found\n";
            exit(1);
        }
        printTask($tasks[$index]);
        break;

    default:
        echo "Invalid action\n";
        break;
}
