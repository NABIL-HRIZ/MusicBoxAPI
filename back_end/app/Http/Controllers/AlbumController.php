<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Artist;

class AlbumController extends Controller
{
    // --- Get all albums ---
    /**
     * @OA\Get(
     *     path="/albums",
     *     tags={"Albums"},
     *     summary="Get all albums",
     *     description="Retrieve a list of all albums with their artist",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of albums",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Album 1"),
     *                 @OA\Property(property="annee", type="integer", example=2025),
     *                 @OA\Property(property="artist_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $albums = Album::with('artist')->get();
        return response()->json($albums, 200);
    }

    // --- Get album details ---
    /**
     * @OA\Get(
     *     path="/albums/{id}",
     *     tags={"Albums"},
     *     summary="Get album details",
     *     description="Retrieve details of a specific album by ID",
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
     *         description="Album details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="titre", type="string", example="Album 1"),
     *             @OA\Property(property="annee", type="integer", example=2025),
     *             @OA\Property(property="artist_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Album not found"
     *     )
     * )
     */
    public function show($id)
    {
        $album = Album::with('artist')->find($id);

        if (!$album) {
            return response()->json(['message' => 'Album not found'], 404);
        }

        return response()->json($album, 200);
    }

    // --- Create album ---
    /**
     * @OA\Post(
     *     path="/albums",
     *     tags={"Albums"},
     *     summary="Create a new album",
     *     description="Store a newly created album",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titre","annee","artist_id"},
     *             @OA\Property(property="titre", type="string", example="Album 1"),
     *             @OA\Property(property="annee", type="integer", example=2025),
     *             @OA\Property(property="artist_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Album created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="titre", type="string", example="Album 1"),
     *             @OA\Property(property="annee", type="integer", example=2025),
     *             @OA\Property(property="artist_id", type="integer", example=1)
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
            'annee' => 'required|integer',
            'artist_id' => 'required|exists:artists,id',
        ]);

        $album = Album::create($request->only(['titre','annee','artist_id']));

        return response()->json([
            'message' => 'Album created successfully',
            'album' => $album
        ], 201);
    }

    // --- Update album ---
    /**
     * @OA\Put(
     *     path="/albums/{id}",
     *     tags={"Albums"},
     *     summary="Update an album",
     *     description="Update the details of an existing album by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Album ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="titre", type="string", example="Updated Album"),
     *             @OA\Property(property="annee", type="integer", example=2026),
     *             @OA\Property(property="artist_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Album updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Album updated successfully"),
     *             @OA\Property(property="album", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Album not found"
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
            'annee' => 'sometimes|integer',
            'artist_id' => 'sometimes|exists:artists,id',
        ]);

        $album = Album::find($id);

        if (!$album) {
            return response()->json(['message' => 'Album not found'], 404);
        }

        $album->update($request->only(['titre','annee','artist_id']));

        return response()->json([
            'message' => 'Album updated successfully',
            'album' => $album->refresh()
        ], 200);
    }

    // --- Delete album ---
    /**
     * @OA\Delete(
     *     path="/albums/{id}",
     *     tags={"Albums"},
     *     summary="Delete an album",
     *     description="Delete an existing album by ID",
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
     *         description="Album deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Album deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Album not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $album = Album::find($id);

        if (!$album) {
            return response()->json(['message' => 'Album not found'], 404);
        }

        $album->delete();

        return response()->json([
            'message' => 'Album deleted successfully'
        ], 200);
    }
}
