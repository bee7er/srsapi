<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const PAGE_AREA_HOME = 'home';
    const PAGE_AREA_CONTACT = 'contact';
    const PAGE_AREA_PAGES = 'pages';
    const PAGE_AREA_RESOURCES = 'resources';
    const PAGE_AREA_USERS = 'users';

    static $validPageAreas = [
        self::PAGE_AREA_HOME,
        self::PAGE_AREA_CONTACT,
        self::PAGE_AREA_PAGES,
        self::PAGE_AREA_RESOURCES,
        self::PAGE_AREA_USERS
    ];

    /**
     * Check for valid view name.  If recognised return it as a valid menu option prefix.
     *
     * @param $viewName
     * @return string
     */
    public static function getActiveMenuOption($viewName)
    {
//        dd($viewName);
        if ($viewName) {
            $pageArea = explode('.', $viewName)[0];
//            dd($pageArea);
            if (in_array($pageArea, self::$validPageAreas)) {
                return $pageArea;
            }
        }
        return self::PAGE_AREA_HOME;        # Default
    }
}
