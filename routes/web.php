<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    $todos = session('todos', []);

    return view('index', compact('todos'));
})->name('todo.index');

Route::post('/todos/add', function (Request $request) {
    $request->validate([
        'task' => 'required|string|max:255',
        'date' => 'required|date',
    ]);

    $todos = session('todos', []);

    $todos[] = [
        'task' => $request->task,
        'status' => false,
        'priority' => $request->priority ?? 'medium',
        'date' => $request->date,
    ];

    session(['todos' => $todos]);

    return redirect()->route('todo.index');
});

Route::post('/todos/delete/{index}', function ($index) {
    $todos = session('todos', []);

    if (isset($todos[$index])) {
        unset($todos[$index]);
    }

    session(['todos' => array_values($todos)]);

    return redirect()->route('todo.index');
});

Route::post('/todos/mark-done/{index}', function ($index) {
    $todos = session('todos', []);

    if (isset($todos[$index])) {
        $todos[$index]['status'] = !$todos[$index]['status'];
    }

    session(['todos' => $todos]);

    return redirect()->route('todo.index');
});

Route::post('/todos/clear', function () {
    session()->flush();

    return redirect()->route('todo.index');
});
