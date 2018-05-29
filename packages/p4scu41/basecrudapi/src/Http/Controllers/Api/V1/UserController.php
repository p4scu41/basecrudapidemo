<?php

namespace p4scu41\BaseCRUDApi\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use p4scu41\BaseCRUDApi\Exceptions\ValidationModelException;
use p4scu41\BaseCRUDApi\Models\User;

/**
 * Controllers Base Class
 *
 * @category Controllers
 * @package  p4scu41\BaseCRUDApi\Http\Controllers\Api\V1
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class UserController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $model = new User();

            $model->fill($request->all());

            $model->validate($request->all());

            $model->save();
        } catch (ValidationModelException $e) {
            return response()->json($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id Resource primary key
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json('Data not found', 404);
        } catch (Exception $e) {
            return response()->json(['exception' => get_class($e), 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Request instance
     * @param int                      $id      Resource primary key
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $model = User::findOrFail($id);

            $model->fill($request->all());

            $model->validate();

            $model->save();
        } catch (ValidationModelException $e) {
            return response()->json($e->getMessage(), $e->getCode());
        } catch (ModelNotFoundException $e) {
            return response()->json('Data not found', 404);
        } catch (Exception $e) {
            return response()->json(['exception' => get_class($e), 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Resource primary key
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $model = User::findOrFail($id);

            $model->delete();
        } catch (ModelNotFoundException $e) {
            return response()->json('Data not found', 404);
        } catch (Exception $e) {
            return response()->json(['exception' => get_class($e), 'message' => $e->getMessage()], 500);
        }
    }
}
