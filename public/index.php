<?php

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use App\Http\Route;

// echo "hhhhhs";
// Load routes from routes/v1/api.php
require_once __DIR__ . '/../routes/v1/api.php';

Route::handle();