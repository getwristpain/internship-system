<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Mentorship;

class MentorshipService
{
    public static function findMentorship(?int $id = null)
    {
        if ($id) {
            return Mentorship::find($id) ?? null;
        }

        return null;
    }

    public static function getPaginatedMentorships(?string $userId = null, string $search = '', int $perPage = 20)
    {
        if (empty($userId)) {
            return collect(); // Return empty collection if userId is empty
        }

        $user = User::find($userId);

        if (!$user) {
            return collect(); // Return empty collection if user not found
        }

        // Build mentorship query with optional search filter
        $query = $user->mentorships();


        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('content', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // Order and paginate journals, then transform results
        $paginatedMentorships = $query->orderBy('date', 'desc')->paginate($perPage);

        $paginatedMentorships->getCollection()->transform(function ($journal) {
            return self::transformJournal($journal, 'paginated');
        });

        return $paginatedMentorships->isEmpty() ? collect() : $paginatedMentorships;
    }

    private static function transformJournal($item, string $context)
    {
        // Set the date format based on the context
        $dateFormat = $context === 'paginated' ? 'l, d F Y' : 'm-d-Y';

        $item->date = Carbon::parse($item->date)->translatedFormat($dateFormat);
        return $item;
    }
}
