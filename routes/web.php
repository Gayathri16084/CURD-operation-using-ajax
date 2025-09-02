<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;


Route::get('/', [EmployeeController::class, 'index']);
Route::get('/employees', [EmployeeController::class, 'getEmployees'])->name('employees.list');
Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
Route::post('/employees/update', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
