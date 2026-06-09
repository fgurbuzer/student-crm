<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deals;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class ClientsController extends Controller
{
     public function __construct(

    ) {}
    /**
     * Display a listing of the resource.
     */
    public function listClients(Request $request)
    {
        $user_id = Auth::id();


        $allrequest = $request->all();

        $query = User::query()
        ->role('client')
        ->with('userInfo')
        ->withCount([
            'threads',
            'deals',
        ])
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            });
        })
        ->where(function($q) use ($allrequest){
            if(count($allrequest) > 0){
                foreach($allrequest as $key => $field){
                    if($field != '' && !empty($field) && $key != 'page' && $key != 'pagesize'){


                        if($key === 'search'){
                            $q->orWhere('name','LIKE', '%'.$field.'%');

                        }

                    }

                }
            }

        });

        $pagesize = $request['pagesize'];
        $sortby = $request['sortBy'];

        $table_data = $query->select('users.*')->orderBy('created_at','desc')
            ->paginate($pagesize);

        //$total = $table_data->total();

        return json_encode($table_data);
    }






}
