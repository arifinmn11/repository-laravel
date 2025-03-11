<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});


// Route::any('{catchall}', function () {
//     return abort(404);
// })->where('catchall', '.*');

require __DIR__ . '/auth.php';

require __DIR__.'/prezet.php';