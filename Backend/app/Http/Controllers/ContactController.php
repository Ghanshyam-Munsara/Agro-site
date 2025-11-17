<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Contact service instance.
     *
     * @var ContactService
     */
    protected $contactService;

    /**
     * Create a new controller instance.
     *
     * @param ContactService $contactService
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContactRequest $request
     * @return JsonResponse
     */
    public function store(ContactRequest $request)
    {
        try {
            $contact = $this->contactService->submitContactForm($request->validated(), $request);

            return response()->json([
                'success' => true,
                'message' => "Thank you for contacting us. We'll get back to you within 24 hours.",
                'data' => ContactResource::public($contact)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * Display a listing of the resource (Admin only).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        // TODO: Add admin authentication middleware

        $filters = [
            'status' => $request->get('status'),
            'subject' => $request->get('subject'),
            'search' => $request->get('search'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'sort' => $request->get('sort', 'created_at'),
            'order' => $request->get('order', 'desc'),
        ];

        $query = $this->contactService->searchContacts($filters);
        $perPage = min($request->get('per_page', 20), 100);
        $contacts = $query->paginate($perPage);

        return ContactResource::collection($contacts);
    }

    /**
     * Display the specified resource (Admin only).
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        // TODO: Add admin authentication middleware

        $contact = Contact::findOrFail($id);

        // Mark as read if it's new
        if ($contact->isNew()) {
            $contact = $this->contactService->markAsRead($contact);
        }

        return response()->json([
            'success' => true,
            'data' => new ContactResource($contact)
        ]);
    }

    /**
     * Update the contact status (Admin only).
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // TODO: Add admin authentication middleware

        $request->validate([
            'status' => 'required|in:new,read,replied,archived'
        ]);

        $contact = Contact::findOrFail($id);
        
        if ($request->status === Contact::STATUS_REPLIED) {
            $contact = $this->contactService->markAsReplied($contact);
        } elseif ($request->status === Contact::STATUS_ARCHIVED) {
            $contact = $this->contactService->archiveContact($contact);
        } else {
            $contact->update(['status' => $request->status]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Contact status updated successfully.',
            'data' => new ContactResource($contact->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage (Admin only).
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        // TODO: Add admin authentication middleware

        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully.'
        ]);
    }

    /**
     * Get contact statistics (Admin only).
     *
     * @return JsonResponse
     */
    public function statistics()
    {
        // TODO: Add admin authentication middleware

        $stats = $this->contactService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
