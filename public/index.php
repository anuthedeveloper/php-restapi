<?php
// Load environment variables and any required bootstrap code
require_once __DIR__ . '/../bootstrap/app.php';

use App\Http\Route;

require_once __DIR__ . '/../routes/api.php';

Route::handle();