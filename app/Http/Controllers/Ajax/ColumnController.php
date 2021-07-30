<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumnController extends Controller
{
    public function index()
    {
        return $this->sendSuccessResponse(
            "Data fetched successfully", Column::with('cards')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:120'
        ]);

        try {
            $column = Column::create($request->only('title'));
            return $this->sendSuccessResponse(
                "Column added successfully!",
                $column->load('cards'),
                201
            );
        } catch (\Exception $exception) {
            return $this->sendFailureResponse(
                'Something went wrong!'
            );
        }
    }

    public function destroy(int $id)
    {
        try {
            $column = Column::find($id);

            DB::transaction(function () use ($column) {
                $column->cards()->delete();
            });

            $column->delete();

            return $this->sendSuccessResponse(
                "Data deleted successfully", []
            );
        } catch (\Exception $exception) {
            return $this->sendFailureResponse(
                'Failed to delete data'
            );
        }
    }
}
