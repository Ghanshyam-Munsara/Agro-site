<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Service service instance.
     *
     * @var ServiceService
     */
    protected $serviceService;

    /**
     * Create a new controller instance.
     *
     * @param ServiceService $serviceService
     */
    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $filters = [
            'category' => $request->get('category'),
            'status' => $request->get('status'),
            'search' => $request->get('search'),
            'sort' => $request->get('sort', 'created_at'),
            'order' => $request->get('order', 'desc'),
        ];

        $query = $this->serviceService->searchServices($filters);
        $perPage = min($request->get('per_page', 15), 100);
        $services = $query->paginate($perPage);

        return ServiceResource::collection($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServiceRequest $request
     * @return JsonResponse
     */
    public function store(ServiceRequest $request)
    {
        $data = $request->validated();
        
        // Handle image file if uploaded
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $service = $this->serviceService->createService($data);

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully.',
            'data' => new ServiceResource($service)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new ServiceResource($service)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ServiceRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ServiceRequest $request, $id)
    {
        $service = Service::findOrFail($id);
        $data = $request->validated();
        
        // Handle image file if uploaded
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $service = $this->serviceService->updateService($service, $data);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully.',
            'data' => new ServiceResource($service)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $this->serviceService->deleteService($service);

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully.'
        ]);
    }

    /**
     * Update active clients count.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateClients(Request $request, $id)
    {
        $request->validate([
            'active_clients' => 'required|integer|min:0'
        ]);

        $service = Service::findOrFail($id);
        $service = $this->serviceService->updateActiveClients($service, $request->active_clients);

        return response()->json([
            'success' => true,
            'message' => 'Active clients updated successfully.',
            'data' => new ServiceResource($service)
        ]);
    }
}
