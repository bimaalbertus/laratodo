<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route untuk menampilkan halaman utama (index)
Route::get('/', function () {
    // Mengambil data todos yang disimpan dalam session, atau array kosong jika tidak ada
    $todos = session('todos', []);

    // Mengembalikan tampilan 'index' dan mengirim data todos ke tampilan
    return view('index', compact('todos'));
})->name('todo.index');

// Route untuk menambah todo baru
Route::post('/todos/add', function (Request $request) {
    // Validasi input dari form
    $request->validate([
        'task' => 'required|string|max:255',  // Tugas harus berupa string dan tidak lebih dari 255 karakter
        'date' => 'required|date',  // Tanggal harus valid
    ]);

    // Mengambil todos dari session, atau array kosong jika belum ada
    $todos = session('todos', []);

    // Menambahkan todo baru ke dalam array todos
    $todos[] = [
        'task' => $request->task,  // Tugas yang diinputkan oleh pengguna
        'status' => false,  // Status todo, defaultnya 'belum selesai'
        'priority' => $request->priority ?? 'medium',  // Prioritas todo, defaultnya 'medium'
        'date' => $request->date,  // Tanggal todo
    ];

    // Menyimpan array todos yang sudah diperbarui kembali ke session
    session(['todos' => $todos]);

    // Mengarahkan kembali ke halaman utama (index)
    return redirect()->route('todo.index');
});

// Route untuk menghapus todo berdasarkan indeks
Route::post('/todos/delete/{index}', function ($index) {
    // Mengambil todos dari session
    $todos = session('todos', []);

    // Memeriksa apakah todo dengan indeks yang diberikan ada
    if (isset($todos[$index])) {
        // Menghapus todo dari array
        unset($todos[$index]);
    }

    // Menyimpan todos yang sudah dihapus kembali ke session, dan reset indeks array
    session(['todos' => array_values($todos)]);

    // Mengarahkan kembali ke halaman utama (index)
    return redirect()->route('todo.index');
});

// Route untuk menandai todo sebagai selesai/belum selesai
Route::post('/todos/mark-done/{index}', function ($index) {
    // Mengambil todos dari session
    $todos = session('todos', []);

    // Memeriksa apakah todo dengan indeks yang diberikan ada
    if (isset($todos[$index])) {
        // Membalikkan status todo (dari selesai menjadi belum selesai, atau sebaliknya)
        $todos[$index]['status'] = !$todos[$index]['status'];
    }

    // Menyimpan todos yang sudah diperbarui kembali ke session
    session(['todos' => $todos]);

    // Mengarahkan kembali ke halaman utama (index)
    return redirect()->route('todo.index');
});

// Route untuk menghapus semua todo
Route::post('/todos/clear', function () {
    // Menghapus semua data dari session
    session()->flush();

    // Mengarahkan kembali ke halaman utama (index)
    return redirect()->route('todo.index');
});
