<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatusEnum::class,
        ];
    }

    protected $fillable =[
        'title',
        'description',
        'status',
        'due_date',
        'created_by'
    ];

    /**
     * Filter task
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('title', 'like', '%' . $search . '%');
        })
            ->when($filters['assignee'] ?? null, function ($query, $assignee) {
                $query->whereHas('assignees', function ($q) use ($assignee) {
                    $q->where('user_id', $assignee);
                });
            })
            ->when($filters['due_date'] ?? null, function ($query, $dueDate) {
                $query->where('due_date', $dueDate);
            });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }
}
