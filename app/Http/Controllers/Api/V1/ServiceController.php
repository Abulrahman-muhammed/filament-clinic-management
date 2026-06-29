<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ServiceResource;
class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        $services = Service::where('is_active', true)->latest()->get();
        if ($services->isEmpty()) {
            return ApiResponse::sendResponse(404, 'No services found', []);
        }
        return ApiResponse::sendResponse(200, 'Services fetched successfully', ServiceResource::collection($services));
    }
}
