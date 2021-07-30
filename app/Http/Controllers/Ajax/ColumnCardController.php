<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumnCardController extends Controller
{
    public function store(Request $request, int $id)
    {
        $request->validate([
            'title'       => 'required|max:120',
            'description' => 'required'
        ]);

        try {
            $column = Column::find($id);

            $card = $column->cards()->create($request->only(['title', 'description']));

            return $this->sendSuccessResponse(
                "Card added successfully!",
                $card,
                201
            );
        } catch (\Exception $exception) {
            return $this->sendFailureResponse(
                'Failed to add card'
            );
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|max:120',
            'description' => 'required'
        ]);

        try {
            $card = Card::find($id);
            $card->update($request->only(['title', 'description']));

            return $this->sendSuccessResponse(
                "Card added successfully!",
                $card->refresh()
            );
        } catch (\Exception $exception) {
            return $this->sendFailureResponse(
                'Failed to update card'
            );
        }
    }
}
