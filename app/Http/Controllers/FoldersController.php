<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFolderPostRequest;
use App\Http\Requests\UpdateFolderPutRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Json;

class FoldersController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sortDirection = $request->input("sort");
        $filter = $request->input("filter");
        $column = $request->input("columnName");
        $query = Folder::query();

        if($filter){
            $query->whereRaw( "" . $column . " LIKE '%" . $filter .  "%'");
        }
        if($sortDirection) {
            $query->orderBy($column, $sortDirection);
        }

        $response = $query->paginate(10);
        return response()->json($response, 200);
    }

    public function show(Folder $folder): JsonResponse
    {
        $response = $folder->notes()->get();

        return response()->json($response, 200);
    }

    public function store(StoreFolderPostRequest $request): JsonResponse
    {
        $id = Auth::id();

        $folder = Folder::create([
            'user_id' => $id,
            'title' => $request->input('title')
            ]);

        return response()->json($folder, 201);
    }

    public function update(UpdateFolderPutRequest $request, Folder $folder): JsonResponse
    {
        $id = Auth::id();
        $userIdOfFolder = $folder->getAttributeValue('user_id');

        if ($id == $userIdOfFolder) {
            $folder->update([
                'title' => $request->input("title")
            ]);
        }
        return response()->json($folder, 200);
    }

    public function delete(Folder $folder): JsonResponse
    {
        $id = Auth::id();
        $userIdOfFolder = $folder->getAttributeValue('user_id');

        if ($id == $userIdOfFolder){
            $folder->delete();
        }
        else { return response()->json("unauthorized", 401); }

        return response()->json("deleted", 204);
    }
}
