<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RfcComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rfc_id',
        'user_id',
        'comment',
        'is_internal',
        'parent_id',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    protected $with = ['user'];

    /**
     * Get the RFC that owns the comment.
     */
    public function rfc(): BelongsTo
    {
        return $this->belongsTo(PermohonanRfc::class, 'rfc_id');
    }

    /**
     * Get the user that created the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for replies).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(RfcComment::class, 'parent_id');
    }

    /**
     * Get the replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(RfcComment::class, 'parent_id')->orderBy('created_at');
    }

    /**
     * Scope to get only public comments.
     */
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    /**
     * Scope to get only internal notes.
     */
    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    /**
     * Scope to get only root comments (no parent).
     */
    public function scopeRootComments($query)
    {
        return $query->whereNull('parent_id');
    }
}
