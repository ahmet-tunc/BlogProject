<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Service\RouteCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $list = Menu::orderBy('order', 'ASC')->get();
        return view('admin.menu', compact('list'));
    }

    public function add(Request $request)
    {
        $routeType = intval($request->route_type);
        $route = $request->route;
        $routeCheck = RouteCheckService::checkRoute($route,$routeType);

        if($routeCheck)
        {
            if (Menu::where('order', '>=', $request->order)->exists())
            {
                DB::update('Update menu set menu.order=menu.order+1 where menu.order>=?', [$request->order]);
            }

            Menu::create([
                'name' => $request->name,
                'order' => $request->order,
                'status' => isset($request->status) ? 1 : 0,
                'route_type' => $routeType,
                'route' => $route,
                'user_id' => auth()->id()
            ]);

            alert()->success('Başarılı', 'Menü eklendi.')
                ->showConfirmButton('Tamam', '#3085d6');

            return redirect()->route('menu.index');

        }

        alert()->warning('Uyarı', 'Girilen route değeri bulunamadı.')
            ->showConfirmButton('Tamam', '#3085d6');

        return redirect()->back();

    }

    public function editOrder(Request $request)
    {
        $trID = $request->trID;
        $currentID = $request->currentID;
        $newID = $request->newID;

        DB::update('Update menu set menu.order=? where id=?', [$newID, $trID]);

        if ($currentID < $newID)
        { //1,3
            //2,3
            DB::update('Update menu set menu.order=menu.order-1 where id<>? and menu.order between ? and ?', [$trID, $currentID, $newID]);
        }
        else
        { //3,1
            //1,2
            DB::update('Update menu set menu.order=menu.order+1 where id<>? and menu.order between ? and ?', [$trID, $newID, $currentID]);
        }

    }

    public function editShow(Request $request)
    {
        $id = $request->id;
        $menu = Menu::find($id);

        return response()->json(['menu' => $menu], 200);
    }

    public function edit(Request $request)
    {
        $routeType = intval($request->route_type);
        $route = $request->route;
        $routeCheck = RouteCheckService::checkRoute($route,$routeType);

        if($routeCheck)
        {

            $id = $request->id;
            Menu::where('id', $id)->update([
                'name' => $request->name,
                'order' => $request->order,
                'status' => isset($request->status) ? 1 : 0,
                'route_type' => $routeType,
                'route' => $route,
            ]);

            alert()->success('Başarılı', 'Menü güncellendi.')
                ->showConfirmButton('Tamam', '#3085d6');

            return redirect()->route('menu.index');
        }
        alert()->warning('Uyarı', 'Girilen route değeri bulunamadı.')
            ->showConfirmButton('Tamam', '#3085d6');

        return redirect()->back();
    }

}
