<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class ContactService
{
    /**
     * Maximum contact submissions per hour per IP.
     */
    const MAX_ATTEMPTS_PER_HOUR = 5;

    /**
     * Submit contact form.
     *
     * @param array $data
     * @param Request $request
     * @return Contact
     * @throws \Exception
     */
    public function submitContactForm(array $data, Request $request): Contact
    {
        // Check rate limiting
        $this->checkRateLimit($request);

        // Spam detection
        $this->detectSpam($data, $request);

        // Create contact submission
        $contact = Contact::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => Contact::STATUS_NEW,
        ]);

        // Increment rate limiter
        $this->incrementRateLimit($request);

        // TODO: Send emails (will be implemented in Phase 8)
        // $this->sendAutoReplyEmail($contact);
        // $this->sendAdminNotificationEmail($contact);

        return $contact;
    }

    /**
     * Check rate limiting for contact form.
     *
     * @param Request $request
     * @return void
     * @throws \Exception
     */
    protected function checkRateLimit(Request $request): void
    {
        $key = 'contact_form:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS_PER_HOUR)) {
            $seconds = RateLimiter::availableIn($key);
            throw new \Exception("Too many contact form submissions. Please try again in {$seconds} seconds.", 429);
        }
    }

    /**
     * Increment rate limiter.
     *
     * @param Request $request
     * @return void
     */
    protected function incrementRateLimit(Request $request): void
    {
        $key = 'contact_form:' . $request->ip();
        RateLimiter::hit($key, 3600); // 1 hour
    }

    /**
     * Detect spam submissions.
     *
     * @param array $data
     * @param Request $request
     * @return void
     * @throws \Exception
     */
    protected function detectSpam(array $data, Request $request): void
    {
        // Check for suspicious patterns
        $suspiciousPatterns = [
            'http://', 'https://', 'www.', '.com', '.net', '.org', // URLs
            'click here', 'buy now', 'limited time', // Marketing phrases
        ];

        $message = strtolower($data['message'] ?? '');
        $name = strtolower($data['name'] ?? '');

        foreach ($suspiciousPatterns as $pattern) {
            if (strpos($message, $pattern) !== false || strpos($name, $pattern) !== false) {
                Log::warning('Spam detected in contact form', [
                    'ip' => $request->ip(),
                    'email' => $data['email'] ?? 'N/A',
                    'pattern' => $pattern,
                ]);
                // You can either block or flag as spam
                // throw new \Exception('Spam detected. Your submission has been blocked.', 400);
            }
        }

        // Check for repeated characters (e.g., "aaaaaa", "111111")
        if (preg_match('/(.)\1{5,}/', $message) || preg_match('/(.)\1{5,}/', $name)) {
            Log::warning('Suspicious pattern detected in contact form', [
                'ip' => $request->ip(),
                'email' => $data['email'] ?? 'N/A',
            ]);
        }

        // Check for too many submissions from same email in short time
        $recentSubmissions = Contact::where('email', $data['email'])
            ->where('created_at', '>=', now()->subHours(1))
            ->count();

        if ($recentSubmissions >= 3) {
            Log::warning('Multiple submissions from same email', [
                'email' => $data['email'],
                'count' => $recentSubmissions,
            ]);
            // Optionally block or flag
        }
    }

    /**
     * Search contacts with filters.
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchContacts(array $filters = [])
    {
        $query = Contact::query();

        // Apply status filter
        if (isset($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        // Apply subject filter
        if (isset($filters['subject'])) {
            $query->bySubject($filters['subject']);
        }

        // Apply search term
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Apply date range
        if (isset($filters['date_from']) || isset($filters['date_to'])) {
            $query->byDateRange(
                $filters['date_from'] ?? null,
                $filters['date_to'] ?? null
            );
        }

        // Apply sorting
        $sortBy = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';

        if (in_array($sortBy, ['name', 'email', 'created_at', 'status'])) {
            $query->orderBy($sortBy, $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    /**
     * Mark contact as read.
     *
     * @param Contact $contact
     * @return Contact
     */
    public function markAsRead(Contact $contact): Contact
    {
        $contact->markAsRead();
        return $contact->fresh();
    }

    /**
     * Mark contact as replied.
     *
     * @param Contact $contact
     * @param int|null $adminId
     * @return Contact
     */
    public function markAsReplied(Contact $contact, ?int $adminId = null): Contact
    {
        $contact->markAsReplied($adminId);
        return $contact->fresh();
    }

    /**
     * Archive contact.
     *
     * @param Contact $contact
     * @return Contact
     */
    public function archiveContact(Contact $contact): Contact
    {
        $contact->archive();
        return $contact->fresh();
    }

    /**
     * Get new contacts count.
     *
     * @return int
     */
    public function getNewContactsCount(): int
    {
        return Contact::new()->count();
    }

    /**
     * Get unread contacts count.
     *
     * @return int
     */
    public function getUnreadContactsCount(): int
    {
        return Contact::unread()->count();
    }

    /**
     * Get contacts statistics.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'total' => Contact::count(),
            'new' => Contact::new()->count(),
            'read' => Contact::byStatus(Contact::STATUS_READ)->count(),
            'replied' => Contact::byStatus(Contact::STATUS_REPLIED)->count(),
            'archived' => Contact::byStatus(Contact::STATUS_ARCHIVED)->count(),
            'today' => Contact::whereDate('created_at', today())->count(),
            'this_week' => Contact::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Contact::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }
}

