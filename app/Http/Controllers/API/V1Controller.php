<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\ApiRepo as Repo;

class V1Controller extends Controller
{

    private $apiRepi;

    public function __construct(Repo $apiRepi)
    {
        $this->apiRepi = $apiRepi;
    }


    /**
     * Update Setting
     * @OA\Patch (
     *     path="/api/setting",
     *     tags={"Setting Update"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="key",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="value",
     *                          type="integer"
     *                      )
     *                 ),
     *                 example={
     *                     "key":"overtime_method",
     *                     "value":1
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="key", type="string", example="overtime_method"),
     *              @OA\Property(property="value", type="string", example=1),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *          )
     *      )
     * )
     */
    public function updateSetting(Request $request)
    {
        $data = $request->all();
        return $this->apiRepi->updateSetting($data);
    }


    /**
     * Create Employess
     * @OA\Post (
     *     path="/api/employees",
     *     tags={"Create Employees"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="salary",
     *                          type="integer"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"ahmad",
     *                     "salary":3000000
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="ahmad"),
     *              @OA\Property(property="salary", type="integer", example=3000000),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    public function storeEmployees(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|min:2|unique:employees',
            'salary' => 'required|integer|min:2000000|max:10000000',
        ]);

        $emplo = $this->apiRepi->storeEmployees($request->all());
        return response()->json($emplo);
    }


     /**
     * Create Overtimes
     * @OA\Post (
     *     path="/api/overtimes",
     *     tags={"Create Overtimes"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="employees_id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="date",
     *                          type="date"
     *                      ),
     *                      @OA\Property(
     *                          property="time_started",
     *                          type="time"
     *                      ),
     *                       @OA\Property(
     *                          property="time_ended",
     *                          type="time"
     *                      )
     *                 ),
     *                 example={
     *                     "employees_id":1,
     *                     "date":"2022-10-10",
     *                     "time_started" : "18:00",
     *                     "time_ended" : "20:30"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="employees_id", type="integer", example=1),
     *              @OA\Property(property="date", type="date", example="2022-10-10"),
     *              @OA\Property(property="time_started", type="time", example="18:00"),
     *              @OA\Property(property="time_ended", type="time", example="20:00"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    public function storeOvertimes(Request $request)
    {
        $request->validate([
            'employees_id'   => 'required|integer',
            'date' => 'required|date',
            'time_started' => 'date_format:H:i',
            'time_ended' => 'date_format:H:i|after:time_started'
        ]);
        return $emplo = $this->apiRepi->storeOvertimes($request->all());
    }

      /**
     * Get Overtimes Calculate
     * @OA\Get (
     *     path="/api/overtimes-pay/calculate/{date}",
     *     tags={"Overtimes Calculate"},
     *     @OA\Parameter(
     *         in="path",
     *         name="date",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="title", type="string", example="title"),
     *              @OA\Property(property="content", type="string", example="content"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function overtimePay($data)
    {
        return $this->apiRepi->overtimePay($data);

    }
}
