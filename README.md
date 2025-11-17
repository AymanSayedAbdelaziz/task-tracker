# Task Tracker CLI - PHP

A simple **Command-Line Interface (CLI) application** to manage tasks (To-Do List) using **PHP** and a **JSON file** for data storage.

---

## 🚀 Features

* Add a new task
* Update an existing task
* Delete a task
* List tasks by status (`todo` / `in_progress` / `completed` / `all`)
* Show details of a single task
* Store tasks in a JSON file (`tasks.js`)
* Track task creation and update timestamps

---

## 📁 Project Structure

```
task-tracker/
│── data/
│   └── JS/
│       └── tasks.js
│── task.php
├── README.md
└── .gitignore
```

---

## 📥 Requirements

* PHP 8+
* Terminal / CMD
* Write permissions for the `data/JS/tasks.js` file

---

## ⚙️ Usage

### Add a new task

```bash
php task.php add "Task title" todo
```

### Update a task

```bash
php task.php update 3 "New title" completed
```

### Delete a task

```bash
php task.php delete 3
```

### List tasks

```bash
php task.php list all
php task.php list todo
php task.php list in_progress
php task.php list completed
```

### Show a single task

```bash
php task.php show 2
```

---

## 🧠 Data Format (tasks.js)

```json
[
    {
        "id": 1,
        "title": "Study physics",
        "status": "todo",
        "created_at": "2025-11-16 21:15:00",
        "updated_at": null
    }
]
```

---

## 🛠️ Helper Functions

* `loadTasks($file)` → Load tasks from JSON
* `saveTasks($file, $tasks)` → Save tasks to JSON
* `nextId($tasks)` → Generate the next task ID
* `findIndexById($tasks, $id)` → Find a task by ID
* `printTask($task)` → Print a task in formatted table

---

## 🤝 Contributing

This project is open for improvements, such as:

* Adding CLI colors
* Task priorities
* Better user experience

Pull Requests are welcome.

---

## 📄 License

MIT License

---

## 📝 Author

(https://roadmap.sh/projects/task-tracker)



