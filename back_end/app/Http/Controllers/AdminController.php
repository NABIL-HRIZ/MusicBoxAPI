<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

class AdminController extends Controller
{
   /**
 * @OA\Get(
 *     path="/show-artists",
 *     tags={"Artists"},
 *     summary="Get all artists with pagination and filters",
 *     description="Retrieve a paginated list of artists. Supports filters by genre and country.",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page number",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of items per page",
 *         required=false,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Parameter(
 *         name="genre",
 *         in="query",
 *         description="Filter by genre",
 *         required=false,
 *         @OA\Schema(type="string", example="Rap")
 *     ),
 *     @OA\Parameter(
 *         name="pays",
 *         in="query",
 *         description="Filter by country",
 *         required=false,
 *         @OA\Schema(type="string", example="MAROC")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Paginated list of artists",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="current_page", type="integer", example=1),
 *             @OA\Property(property="per_page", type="integer", example=10),
 *             @OA\Property(property="last_page", type="integer", example=5),
 *             @OA\Property(property="total", type="integer", example=50),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Cheb Khaled"),
 *                     @OA\Property(property="genre", type="string", example="Raï"),
 *                     @OA\Property(property="pays", type="string", example="Algérie"),
 *                     @OA\Property(property="created_at", type="string", format="date-time"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
public function getArtists(Request $request) {
    $perPage = $request->input('per_page', 10);

    $query = Artist::query();

    if ($request->has('genre')) {
        $query->where('genre', 'like', '%' . $request->input('genre') . '%');
    }

    if ($request->has('pays')) {
        $query->where('pays','like','%'. $request->input('pays').'%');
    }

    $artists = $query->paginate($perPage);

    return response()->json($artists);
}

    // create artist
    /**
     * @OA\Post(
     *     path="/artists",
     *     tags={"Artists"},
     *     summary="Create a new artist",
     *     description="Store a newly created artist in the database",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","genre","pays"},
     *             @OA\Property(property="name", type="string", example="Cheb Khaled"),
     *             @OA\Property(property="genre", type="string", example="Raï"),
     *             @OA\Property(property="pays", type="string", example="Algérie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Artist created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Cheb Khaled"),
     *             @OA\Property(property="genre", type="string", example="Raï"),
     *             @OA\Property(property="pays", type="string", example="Algérie"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
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
            'name' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'pays' => 'required|string|max:255', 
        ]);

        $artist = Artist::create($request->only(['name', 'genre', 'pays']));

        return response()->json($artist, 201);
    }


    // update artist
    /**
     * @OA\Put(
     *     path="/artists/{id}",
     *     tags={"Artists"},
     *     summary="Update an artist",
     *     description="Update the details of an existing artist by ID",
     *     security={{"sanctum":{}}},
     * @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Artist ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="genre", type="string", example="Updated Genre"),
     *             @OA\Property(property="pays", type="string", example="Maroc")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artist updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="genre", type="string", example="Updated Genre"),
     *             @OA\Property(property="pays", type="string", example="Maroc"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Artist not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function updateArtist(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'genre' => 'sometimes|string|max:255',
            'pays' => 'sometimes|string|max:255',
        ]);

        $artist = Artist::find($id);

        if (!$artist) {
            return response()->json(['message' => 'Artist not found'], 404);
        }

        $artist->update($request->only(['name', 'genre', 'pays']));

      return response()->json([
    'message' => "Successfully updated",
    'artist'  => $artist->refresh()
], 200);
    }


    // delete artist 

    /**
 * @OA\Delete(
 *     path="/artists/{id}",
 *     tags={"Artists"},
 *     summary="Delete an artist",
 *     description="Delete an existing artist by ID",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Artist ID",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Artist deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Artist deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Artist not found"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
 * )
 */
public function deleteArtist($id)
{
    $artist = Artist::find($id);

    if (!$artist) {
        return response()->json([
            'message' => 'Artist not found'
        ], 404);
    }

    $artist->delete();

    return response()->json([
        'message' => 'Artist deleted successfully'
    ], 200);
}



  // GET ARTIST DETAIL
/**
 * @OA\Get(
 *     path="/artists/{id}",
 *     tags={"Artists"},
 *     summary="Get artist details",
 *     description="Retrieve the details of a specific artist by ID",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Artist ID",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Artist details",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Artist name"),
 *             @OA\Property(property="genre", type="string", example="Artist genre "),
 *             @OA\Property(property="pays", type="string", example="Artist pays"),
 *             @OA\Property(property="created_at", type="string", format="date-time"),
 *             @OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Artist not found"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
 * )
 */
public function getArtistDetail($id)
{
    $artist = Artist::find($id);

    if (!$artist) {
        return response()->json([
            'message' => 'Artist not found'
        ], 404);
    }

    return response()->json($artist, 200);
}



// get tous les artistes avec leurs albums

/**
 * @OA\Get(
 *     path="/artists-with-albums",
 *     tags={"Artists"},
 *     summary="Get all artists with their albums",
 *     description="Retrieve a list of all artists along with their associated albums",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of artists with albums",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Cheb Khaled"),
 *                 @OA\Property(property="genre", type="string", example="Raï"),
 *                 @OA\Property(property="pays", type="string", example="Algérie"),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time"),
 *                 @OA\Property(
 *                     property="albums",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="titre", type="string", example="Album 1"),
 *                         @OA\Property(property="annee", type="integer", example=2025),
 *                         @OA\Property(property="artist_id", type="integer", example=1),
 *                         @OA\Property(property="created_at", type="string", format="date-time"),
 *                         @OA\Property(property="updated_at", type="string", format="date-time")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
 * )
 */
public function getArtistsWithAlbums()
{
    $artists = Artist::with('albums')->get();
    return response()->json($artists, 200);
}


// get un specefic artist et ses albums 

/**
 * @OA\Get(
 *     path="/artists/{id}/albums-chansons",
 *     tags={"Artists"},
 *     summary="Get a specific artist with their albums and chansons",
 *     description="Retrieve the details of a specific artist along with all their albums and the songs in each album",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Artist ID",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Artist with albums and chansons",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Cheb Khaled"),
 *             @OA\Property(property="genre", type="string", example="Raï"),
 *             @OA\Property(property="pays", type="string", example="Algérie"),
 *             @OA\Property(property="created_at", type="string", format="date-time"),
 *             @OA\Property(property="updated_at", type="string", format="date-time"),
 *             @OA\Property(
 *                 property="albums",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="titre", type="string", example="Album 1"),
 *                     @OA\Property(property="annee", type="integer", example=2025),
 *                     @OA\Property(property="artist_id", type="integer", example=1),
 *                     @OA\Property(property="created_at", type="string", format="date-time"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time"),
 *                     @OA\Property(
 *                         property="chansons",
 *                         type="array",
 *                         @OA\Items(
 *                             @OA\Property(property="id", type="integer", example=1),
 *                             @OA\Property(property="titre", type="string", example="Song 1"),
 *                             @OA\Property(property="duree", type="number", example=3.5),
 *                             @OA\Property(property="album_id", type="integer", example=1),
 *                             @OA\Property(property="created_at", type="string", format="date-time"),
 *                             @OA\Property(property="updated_at", type="string", format="date-time")
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Artist not found"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
 * )
 */
public function getArtistWithAlbumsAndChansons($id)
{
    $artist = Artist::with('albums.chansons')->find($id);

    if (!$artist) {
        return response()->json(['message' => 'Artist not found'], 404);
    }

    return response()->json($artist, 200);
}






}
