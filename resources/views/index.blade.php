<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <style>
        /* Style untuk body utama */
        body.main-body {
            background-color: #1c1c1c; /* Warna latar belakang */
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 5rem;
            min-height: 100vh;
        }

        /* Container utama */
        .container {
            max-width: 800px;
            width: 100%;
            padding: 1rem;
            margin: 0 auto;
        }

        /* Judul */
        .title {
            text-align: center;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Formulir untuk menambah todo */
        .form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .input-text,
        .input-select,
        .input-date {
            padding: 0.5rem;
            border: 1px solid #555;
            border-radius: 0.5rem;
            background-color: transparent;
            color: white;
            font-size: 1rem;
        }

        .input-text::placeholder,
        .input-select {
            color: #aaa;
        }

        .btn-submit {
            padding: 0.75rem;
            background-color: #d1d1d1;
            color: black;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #a1a1a1;
        }

        /* Tombol untuk menghapus semua tugas */
        .btn-delete-all {
            padding: 0.75rem;
            background-color: #e53e3e;
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
        }

        .btn-delete-all:hover {
            background-color: #c53030;
        }

        /* Daftar todo */
        .todo-list {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* Setiap item todo */
        .todo-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #2d2d2d;
            border-radius: 0.5rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .todo-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Tombol untuk menandai todo selesai */
        .btn-mark-done {
            padding: 0.5rem;
            background-color: transparent;
            border: 1px solid #4caf50;
            border-radius: 0.5rem;
            cursor: pointer;
            color: #4caf50;
            font-size: 0.875rem;
            transition: background-color 0.3s ease;
        }

        .btn-mark-done:hover {
            background-color: #4caf50;
            color: white;
        }

        /* Teks yang sudah selesai */
        .completed {
            text-decoration: line-through;
            color: #999;
        }

        /* Prioritas */
        .priority {
            font-size: 0.875rem;
            color: #aaa;
        }

        /* Tanggal */
        .date {
            font-size: 0.75rem;
            color: #777;
        }

        /* Tombol hapus */
        .btn-delete {
            background: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            border: none;
        }

        .btn-delete:hover {
            color: #e53e3e;
        }

        /* Pesan jika tidak ada todo */
        .empty-message {
            text-align: center;
            color: #bbb;
        }
    </style>
</head>

<body class="main-body">
    <div class="container">
        <!-- Judul Halaman -->
        <h1 class="title">Todo List</h1>

        <!-- Formulir untuk Menambah Todo Baru -->
        <form action="{{ url('/todos/add') }}" method="POST" class="form">
            @csrf <!-- CSRF token untuk mengamankan form -->
            
            <!-- Input untuk Nama Tugas -->
            <input type="text" name="task" placeholder="Input Task" class="input-text" required>

            <!-- Dropdown untuk memilih prioritas tugas -->
            <select name="priority" class="input-select">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>

            <!-- Input untuk memilih tanggal tugas -->
            <input type="date" name="date" class="input-date" required>

            <!-- Tombol untuk menambahkan todo -->
            <button type="submit" class="btn-submit">Add</button>
        </form>

        <!-- Formulir untuk Menghapus Semua Todo jika ada -->
        @if (count($todos) > 0)
            <form action="{{ url('/todos/clear') }}" method="POST">
                @csrf
                <input type="submit" value="Delete All Tasks" class="btn-delete-all">
            </form>
        @endif

        <!-- Daftar Tugas -->
        <div class="todo-list">
            @foreach ($todos as $index => $todo)
                <div class="todo-item">
                    <div class="todo-content">
                        <!-- Formulir untuk menandai todo selesai atau belum -->
                        <form action="{{ url('/todos/mark-done/' . $index) }}" method="POST">
                            @csrf
                            <input type="submit" name="status" value="{{ $todo['status'] ? 'Undone' : 'Done' }}"
                                class="btn-mark-done" />
                        </form>

                        <div>
                            <!-- Menampilkan Tugas dengan Garis Coret jika sudah selesai -->
                            <span class="{{ $todo['status'] ? 'completed' : '' }}">{{ $todo['task'] }}</span>
                            <span class="priority">{{ $todo['priority'] }}</span>
                            <div class="date">{{ $todo['date'] }}</div>
                        </div>
                    </div>

                    <!-- Formulir untuk Menghapus Todo -->
                    <form action="{{ url('/todos/delete/' . $index) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-delete">
                            <i class="ti ti-trash"></i> <!-- Tombol untuk menghapus todo -->
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- Pesan Jika Tidak Ada Todo -->
        @if (count($todos) === 0)
            <p class="empty-message">Nothing to display.</p>
        @endif
    </div>
</body>

</html>
