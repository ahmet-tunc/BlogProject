<?php


namespace App\Service;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use mysql_xdevapi\Exception;

class RouteCheckService
{
    public static function checkRoute(string $route, int $routeType):bool
    {
        if ($routeType==2)
        {
            $routes = Route::getRoutes();
            $myRequest = Request::create($route);
            try
            {
                $routes->match($myRequest);
                $status = true;
            }
            catch (\Exception $exception)
            {
                $status = false;
            }
        }
        else if($routeType==1)
        {
            if(Route::has($route))
            {
                $status = true;
            }
            else
            {
                $status = false;
            }
        }
        return $status;
    }
}
