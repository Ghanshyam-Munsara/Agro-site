<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'ip_address',
        'user_agent',
        'replied_at',
        'replied_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'replied_at' => 'datetime',
    ];

    /**
     * Subject constants
     */
    const SUBJECT_GENERAL = 'general';
    const SUBJECT_SERVICE = 'service';
    const SUBJECT_CONSULTATION = 'consultation';
    const SUBJECT_SUPPORT = 'support';
    const SUBJECT_PARTNERSHIP = 'partnership';
    const SUBJECT_OTHER = 'other';

    /**
     * Status constants
     */
    const STATUS_NEW = 'new';
    const STATUS_READ = 'read';
    const STATUS_REPLIED = 'replied';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Validation rules
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => [
                'required',
                Rule::in([
                    self::SUBJECT_GENERAL,
                    self::SUBJECT_SERVICE,
                    self::SUBJECT_CONSULTATION,
                    self::SUBJECT_SUPPORT,
                    self::SUBJECT_PARTNERSHIP,
                    self::SUBJECT_OTHER,
                ]),
            ],
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    /**
     * Scope: Filter by status
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by subject
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $subject
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    /**
     * Scope: Filter by date range
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDateRange($query, $dateFrom = null, $dateTo = null)
    {
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query;
    }

    /**
     * Scope: Search in name, email, or message
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('email', 'LIKE', "%{$searchTerm}%")
              ->orWhere('message', 'LIKE', "%{$searchTerm}%");
        });
    }

    /**
     * Scope: New contacts only
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    /**
     * Scope: Unread contacts
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->whereIn('status', [self::STATUS_NEW, self::STATUS_READ]);
    }

    /**
     * Accessor: Get formatted created date
     *
     * @return string
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('F j, Y \a\t g:i A');
    }

    /**
     * Accessor: Get formatted replied date
     *
     * @return string|null
     */
    public function getFormattedRepliedAtAttribute()
    {
        return $this->replied_at ? $this->replied_at->format('F j, Y \a\t g:i A') : null;
    }

    /**
     * Accessor: Get subject label
     *
     * @return string
     */
    public function getSubjectLabelAttribute()
    {
        $labels = [
            self::SUBJECT_GENERAL => 'General Inquiry',
            self::SUBJECT_SERVICE => 'Service Information',
            self::SUBJECT_CONSULTATION => 'Free Consultation',
            self::SUBJECT_SUPPORT => 'Technical Support',
            self::SUBJECT_PARTNERSHIP => 'Partnership Opportunity',
            self::SUBJECT_OTHER => 'Other',
        ];

        return $labels[$this->subject] ?? ucfirst($this->subject);
    }

    /**
     * Accessor: Get status label
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_NEW => 'New',
            self::STATUS_READ => 'Read',
            self::STATUS_REPLIED => 'Replied',
            self::STATUS_ARCHIVED => 'Archived',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Mark contact as read
     *
     * @return void
     */
    public function markAsRead()
    {
        if ($this->status === self::STATUS_NEW) {
            $this->update(['status' => self::STATUS_READ]);
        }
    }

    /**
     * Mark contact as replied
     *
     * @param int|null $adminId
     * @return void
     */
    public function markAsReplied($adminId = null)
    {
        $this->update([
            'status' => self::STATUS_REPLIED,
            'replied_at' => now(),
            'replied_by' => $adminId,
        ]);
    }

    /**
     * Archive contact
     *
     * @return void
     */
    public function archive()
    {
        $this->update(['status' => self::STATUS_ARCHIVED]);
    }

    /**
     * Check if contact is new
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->status === self::STATUS_NEW;
    }

    /**
     * Check if contact has been replied
     *
     * @return bool
     */
    public function isReplied()
    {
        return $this->status === self::STATUS_REPLIED;
    }
}
