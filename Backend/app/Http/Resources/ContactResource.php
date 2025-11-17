<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Indicates if the resource should exclude sensitive information.
     *
     * @var bool
     */
    protected $isPublic = false;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @param  bool  $isPublic
     * @return void
     */
    public function __construct($resource, $isPublic = false)
    {
        parent::__construct($resource);
        $this->isPublic = $isPublic;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'subject' => $this->subject,
            'subject_label' => $this->subject_label,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'formatted_created_at' => $this->formatted_created_at,
            'created_at' => $this->created_at ? $this->created_at->toISOString() : null,
        ];

        // For public responses (contact form submission), exclude sensitive data
        if ($this->isPublic) {
            // Only include basic confirmation data
            $data['email'] = $this->maskEmail($this->email);
            $data['message_preview'] = $this->getMessagePreview();
        } else {
            // For admin responses, include all data
            $data['email'] = $this->email;
            $data['phone'] = $this->phone;
            $data['message'] = $this->message;
            $data['formatted_replied_at'] = $this->formatted_replied_at;
            $data['replied_at'] = $this->replied_at ? $this->replied_at->toISOString() : null;
            $data['replied_by'] = $this->replied_by;
            $data['ip_address'] = $this->ip_address;
            $data['user_agent'] = $this->user_agent;
            $data['updated_at'] = $this->updated_at ? $this->updated_at->toISOString() : null;
        }

        return $data;
    }

    /**
     * Mask email address for privacy (public responses).
     *
     * @param string $email
     * @return string
     */
    protected function maskEmail($email)
    {
        if (!$email) {
            return null;
        }

        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        $username = $parts[0];
        $domain = $parts[1];

        // Mask username: show first 2 chars, mask the rest
        $maskedUsername = substr($username, 0, 2) . str_repeat('*', max(0, strlen($username) - 2));

        return $maskedUsername . '@' . $domain;
    }

    /**
     * Get a preview of the message (first 100 characters).
     *
     * @return string
     */
    protected function getMessagePreview()
    {
        if (!$this->message) {
            return null;
        }

        $preview = substr($this->message, 0, 100);
        if (strlen($this->message) > 100) {
            $preview .= '...';
        }

        return $preview;
    }

    /**
     * Create a public version of the resource (for contact form responses).
     *
     * @param  mixed  $resource
     * @return static
     */
    public static function public($resource)
    {
        return new static($resource, true);
    }
}
