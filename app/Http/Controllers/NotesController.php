<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotePostRequest;
use App\Http\Requests\UpdateNotePutRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Json;

class NotesController extends Controller
{

    public function  index(Request $request): JsonResponse
    {

        $id = Auth::id();

        /*return Folder::with(['notes'])
          ->whereRelation('notes', 'private', '=', '0')
          //->orWhere('user_id', '=', $id)
          ->orWhereRelation('notes', 'user_id', '=', $id)
          ->get();*/

//        query = Note::where('private','=', 0)
//         ->orWhere('user_id', '=', $id);

        $query = Folder::with(['notes'])
            ->whereRelation('notes','private','=', 0)
            ->orWhereRelation('notes','user_id', '=', $id);

        $sortDirection = $request->input("sort");
        $filter = $request->input("filter");
        $column = $request->input("columnName");

        if($filter){
            $query->whereRaw( "" . $column . " LIKE '%" . $filter .  "%'");
        }

        if($sortDirection) {
            $query->orderBy($column, $sortDirection);
        }

        $response = $query->paginate(10);
        return response()->json($response, 200);
    }

    public function  publicNotes(Request $request): JsonResponse
    {
        $query = Folder::with(['notes'])
            ->whereRelation('notes', 'private','=', 0);
        $query = Note::where('private','=', 0);

        $sortDirection = $request->input("sort");
        $filter = $request->input("filter");
        $column = $request->input("columnName");

        if($filter){
            $query->whereRaw( "" . $column . " LIKE '%" . $filter .  "%'");
        }

        if($sortDirection) {
            $query->orderBy($column, $sortDirection);
        }

        $response = $query->paginate(10);
        return response()->json($response, 200);
    }

    public function show(Note $note): JsonResponse
    {
        return response()->json($note, 200);
    }

    public function showPublicNote(Note $note): JsonResponse
    {
        if ($note->private == 0){
            return response()->json($note, 200);
        }
        else { return response()->json('unauthorized', 401);}

    }


    // must check if folder id belongs to user
    public function store(StoreNotePostRequest $request): JsonResponse
    {
        //$note = Note::create($request->all());/
        $id = Auth::id();
        $note = Note::create([
            'user_id' => $id,
            'folder_id' => $request->input('folder_id'),
            'private' => $request->input('private'),
            'type' => $request->input('type'),
            'title' => $request->input('title'),
            'text' => $request->input('text')
        ]);

        return response()->json($note, 201);
    }

    public function update(UpdateNotePutRequest $request, Note $note): JsonResponse
    {
        $id = Auth::id();
        $folder = Folder::where('user_id' , '=', $id)
            ->whereId($request->input('folder_id'))
            ->first();

        if ($folder) {
            $note->update($request->all());
            return response()->json($note, 200);
        }
        else { return response()->json('unauthorized', 401);}
    }

    public function delete(Note $note): JsonResponse
    {
        $note->delete();

        return response()->json('deleted', 204);
    }
}
