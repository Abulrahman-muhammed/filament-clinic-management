<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Services\SlotGeneratorService;
use App\Http\Requests\Api\V1\GenerateSlotRequest;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GenerateSlotRequest $request , SlotGeneratorService $slotGeneratorService)
    {
        $date = $request->validated('date');
        $slots = $slotGeneratorService->generateForApi($date);
        if (empty($slots)) {
            return ApiResponse::sendResponse(
                200,
                'No Available Slots',
                []
            );
        }
        return ApiResponse::sendResponse(
                200,
                'Slots fetched successfully',
                $slots
            );
    }

}
