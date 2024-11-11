<?php

namespace App\Http\Middleware;

class Validator
{
    public static function handle(array $input)
    {
        foreach ($input as $key => $value) {
            if (empty($value)) {
                response()->json(['error' => "Field '$key' is required."], 400);
                exit;
            }
            $input[$key] = htmlspecialchars(strip_tags($value));
        }
        return $input;
    }
}
