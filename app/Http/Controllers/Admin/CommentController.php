<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comment = Comment::paginate(10);
        return view('admin.comment_list', compact('comment'));
    }


    public function delete(Request $request)
    {
        $arrayID = $request->id;

        foreach ($arrayID as $itemID)
        {
            $control = Comment::find($itemID);
            if ($control)
            {
                Comment::where('id', $itemID)->first()->delete();
            }
        }

        return response()->json(['status' => 1], 200);

    }

}
