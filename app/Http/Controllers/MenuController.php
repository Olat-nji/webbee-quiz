<?php


namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Models\MenuItem;
use Exception;
use Illuminate\Routing\Controller as BaseController;

class MenuController extends BaseController
{
    /* TODO: complete getMenuItems so that it returns a nested menu structure from the database
    Requirements
    - the eloquent expressions should result in EXACTLY one SQL query no matter the nesting level or the amount of menu items.
    - post process your results in PHP
    - it should work for infinite level of depth (children of childrens children of childrens children, ...)
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints:
    - imagine a maximum of a few hundred menu items
    - partial or not working answers also get graded so make sure you commit what you have

    Sample response on GET /menu:
    ```json
    [
        {
            "id": 1,
            "name": "All events",
            "url": "/events",
            "parent_id": null,
            "created_at": "2021-04-27T15:35:15.000000Z",
            "updated_at": "2021-04-27T15:35:15.000000Z",
            "children": [
                {
                    "id": 2,
                    "name": "Laracon",
                    "url": "/events/laracon",
                    "parent_id": 1,
                    "created_at": "2021-04-27T15:35:15.000000Z",
                    "updated_at": "2021-04-27T15:35:15.000000Z",
                    "children": [
                        {
                            "id": 3,
                            "name": "Illuminate your knowledge of the laravel code base",
                            "url": "/events/laracon/workshops/illuminate",
                            "parent_id": 2,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        },
                        {
                            "id": 4,
                            "name": "The new Eloquent - load more with less",
                            "url": "/events/laracon/workshops/eloquent",
                            "parent_id": 2,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        }
                    ]
                },
                {
                    "id": 5,
                    "name": "Reactcon",
                    "url": "/events/reactcon",
                    "parent_id": 1,
                    "created_at": "2021-04-27T15:35:15.000000Z",
                    "updated_at": "2021-04-27T15:35:15.000000Z",
                    "children": [
                        {
                            "id": 6,
                            "name": "#NoClass pure functional programming",
                            "url": "/events/reactcon/workshops/noclass",
                            "parent_id": 5,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        },
                        {
                            "id": 7,
                            "name": "Navigating the function jungle",
                            "url": "/events/reactcon/workshops/jungle",
                            "parent_id": 5,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        }
                    ]
                }
            ]
        }
    ]
     */

   

    public function getMenuItems()
    {
        $menu_items = MenuItem::all()->groupBy('parent_id');
        return response()->json($this->myCollection($menu_items[null], $menu_items));
    }


    public function myCollection($value, $menu_items_grouped)
    {
        $menu = [];
        foreach ($value as $key => $val) {
            array_push($menu, $this->mySingleCollection($val, $menu_items_grouped));
        }
        return $menu;
    }


    public function mySingleCollection($value, $menu_items_grouped)
    {
        try {
            $children = $this->myCollection($menu_items_grouped[$value->id], $menu_items_grouped);
        } catch (Exception $e) {
            $children = [];
        }


        return [
            "id" => $value->id,
            "name" => $value->name,
            "url" => $value->url,
            "parent_id" => $value->parent_id,
            "created_at" => $value->created_at,
            "updated_at" => $value->updated_at,
            "children" =>  $children
        ];
    }
}
