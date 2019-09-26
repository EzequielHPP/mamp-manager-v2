<?php

Route::get('/', 'DashboardController@dashboard')
     ->name('dashboard');

Route::get('/reset', 'DashboardController@resetProjects')
     ->name('reset');


Route::post('/create-project', 'DashboardController@createProject')
     ->name('create-project');
Route::post('/update-project/{id}', 'DashboardController@updateProject')
     ->name('update-project');
Route::post('/delete-project/{id}', 'DashboardController@deleteProject')
     ->name('delete-project');
