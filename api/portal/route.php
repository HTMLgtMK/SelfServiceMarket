<?php

use think\Route;

Route::resource('portal/categories', 'portal/Categories');
Route::resource('portal/articles', 'portal/Articles');
Route::resource('portal/pages', 'portal/Pages');
Route::resource('portal/userArticles', 'portal/UserArticles');

Route::get('portal/search','portal/Articles/search');
Route::get('portal/articles/my', 'portal/Articles/my');
Route::post('portal/articles/doLike', 'portal/Articles/doLike');
Route::get('portal/tags/:id/articles', 'portal/Tags/articles');
Route::get('portal/tags', 'portal/Tags/index');

Route::post('portal/userArticles/deletes','portal/UserArticles/deletes');