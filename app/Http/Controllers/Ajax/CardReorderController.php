<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardReorderController extends Controller
{
    public function __invoke(Request $request, int $id)
    {
        $positions = $request->get('positions');

        DB::transaction(function () use ($positions, $id) {
            collect($positions)->each(function ($item, $index) use ($id) {
                Card::find($item)->update([
                    'order'     => $index + 1,
                    'column_id' => $id
                ]);
            });
        });
    }
}
