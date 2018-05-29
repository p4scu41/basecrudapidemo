<?php

namespace p4scu41\BaseCRUDApi\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use p4scu41\BaseCRUDApi\Exceptions\ValidationModelException;
use p4scu41\BaseCRUDApi\Http\Controllers\BaseController;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Controllers Base Api Class
 *
 * @category Controllers
 * @package  p4scu41\BaseCRUDApi\Http\Controllers\Api\V1
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BaseApiController extends BaseController
{
    /**
     * BaseRepository
     *
     * @var p4scu41\BaseCRUDApi\Repositories\BaseRepository
     */
    protected $repository;

    /**
     * @param p4scu41\BaseCRUDApi\Repositories\BaseRepository
     */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->repository->paginate();
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
            $this->repository->create($request->all());
        } catch (ValidationModelException $e) {
            return response()->json($e->getMessage(), $e->getCode());
        } catch (ValidatorException $e) {
            return response()->json($e->toArray(), 422);
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
            return $this->repository->findOrFail($id);
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
            $this->repository->update($request->all(), $id);
        } catch (ValidationModelException $e) {
            return response()->json($e->getMessage(), $e->getCode());
        } catch (ValidatorException $e) {
            return response()->json($e->toArray(), 422);
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
            $this->repository->delete($id);
        } catch (ModelNotFoundException $e) {
            return response()->json('Data not found', 404);
        } catch (Exception $e) {
            return response()->json(['exception' => get_class($e), 'message' => $e->getMessage()], 500);
        }
    }
}
