<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chanson;
use App\Models\Album;

class ChansonController extends Controller
{
    // --- Get all chansons ---
    /**
     * @OA\Get(
     *     path="/chansons",
     *     tags={"Chansons"},
     *     summary="Get all chansons",
     *     description="Retrieve a list of all songs",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of songs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Song 1"),
     *                 @OA\Property(property="duree", type="number", format="float", example=3.5),
     *                 @OA\Property(property="album_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $chansons = Chanson::with('album')->get();
        return response()->json($chansons, 200);
    }

    // --- Get chanson by ID ---
    /**
     * @OA\Get(
     *     path="/chansons/{id}",
     *     tags={"Chansons"},
     *     summary="Get a specific chanson",
     *     description="Retrieve details of a specific song by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Chanson ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Song details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="titre", type="string", example="Song 1"),
     *             @OA\Property(property="duree", type="number", format="float", example=3.5),
     *             @OA\Property(property="album_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chanson not found"
     *     )
     * )
     */
    public function show($id)
    {
        $chanson = Chanson::with('album')->find($id);
        if (!$chanson) {
            return response()->json(['message' => 'Chanson not found'], 404);
        }
        return response()->json($chanson, 200);
    }

    // --- Create chanson ---
    /**
     * @OA\Post(
     *     path="/chansons",
     *     tags={"Chansons"},
     *     summary="Create a new chanson",
     *     description="Store a newly created song",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titre","duree","album_id"},
     *             @OA\Property(property="titre", type="string", example="Song 1"),
     *             @OA\Property(property="duree", type="number", format="float", example=3.5),
     *             @OA\Property(property="album_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Chanson created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Chanson created successfully"),
     *             @OA\Property(property="chanson", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'duree' => 'required|numeric',
            'album_id' => 'required|exists:albums,id',
        ]);

        $chanson = Chanson::create($request->only(['titre','duree','album_id']));

        return response()->json([
            'message' => 'Chanson created successfully',
            'chanson' => $chanson
        ], 201);
    }

    // --- Update chanson ---
    /**
     * @OA\Put(
     *     path="/chansons/{id}",
     *     tags={"Chansons"},
     *     summary="Update a chanson",
     *     description="Update an existing song",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="titre", type="string", example="Updated Song"),
     *             @OA\Property(property="duree", type="number", format="float", example=4.0),
     *             @OA\Property(property="album_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chanson updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Chanson updated successfully"),
     *             @OA\Property(property="chanson", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chanson not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'titre' => 'sometimes|string|max:255',
            'duree' => 'sometimes|numeric',
            'album_id' => 'sometimes|exists:albums,id',
        ]);

        $chanson = Chanson::find($id);
        if (!$chanson) return response()->json(['message'=>'Chanson not found'], 404);

        $chanson->update($request->only(['titre','duree','album_id']));

        return response()->json([
            'message' => 'Chanson updated successfully',
            'chanson' => $chanson->refresh()
        ], 200);
    }

    // --- Delete chanson ---
    /**
     * @OA\Delete(
     *     path="/chansons/{id}",
     *     tags={"Chansons"},
     *     summary="Delete a chanson",
     *     description="Delete an existing song",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chanson deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Chanson deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chanson not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $chanson = Chanson::find($id);
        if (!$chanson) return response()->json(['message'=>'Chanson not found'], 404);

        $chanson->delete();

        return response()->json(['message'=>'Chanson deleted successfully'], 200);
    }


    // get chanaon by album 

    /**
 * @OA\Get(
 *     path="/albums/{id}/chansons",
 *     tags={"Albums"},
 *     summary="Get all chansons of a specific album",
 *     description="Retrieve all songs that belong to a specific album",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Album ID",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of chansons for the album",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="titre", type="string", example="Ma Première Chanson"),
 *                 @OA\Property(property="duree", type="number", format="float", example=3.5),
 *                 @OA\Property(property="album_id", type="integer", example=1),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Album not found"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
 * )
 */
public function getChansonsByAlbum($id)
{
    $album = Album::with('chansons')->find($id);

    if (!$album) {
        return response()->json(['message' => 'Album not found'], 404);
    }

    return response()->json($album->chansons, 200);
}

}
