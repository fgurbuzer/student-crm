<?php

namespace App\Http\Controllers;

use App\Models\Deals;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PipelineController extends Controller
{


    public function index(Request $request)
    {

        return Inertia::render('Sales/Pipeline');
    }

    public function getpipeline(Request $request)
    {
        $deals = Deals::query()
            ->with([
                'user:id,name',
                'owner:id,name',
            ])
            ->when(
                $request->user()->hasRole('sales'),
                fn ($q) => $q->where('owner_id', $request->user()->id)
            )
            ->orderBy('created_at')
            ->get();

        return json_encode([
            'columns' => $deals->groupBy('stage'),
        ]);

    }

}
