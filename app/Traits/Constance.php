<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Constance
{

    public static function getMyStorageDir(Request $request): string
    {
        $hostName = $request->getHttpHost();
        if (str_contains($hostName, "192") || str_contains($hostName, "127"))
            return "/storage";
        return "/storage/app/public";
    }
    public static function getMyFilePath(Request $request, string $path): string
    {
        $hostName = $request->getHttpHost();
        if (str_contains($hostName, "192") || str_contains($hostName, "127"))
            return storage_path() ."\\app\\public" . str_replace('/storage', '', $path);
        return "/home/laserstars/public_html" . $path;
    }
}
