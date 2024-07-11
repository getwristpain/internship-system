<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Models\Journal;

class JournalObserver
{
    /**
     * Handle the Journal "created" event.
     */
    public function created(Journal $journal): void
    {
        Attendance::updateOrCreate(
            [
                'student_id' => $journal->student_id,
                'internship_id' => $journal->internship_id,
                'attendance_date' => $journal->journal_date,
            ],
            [
                'status' => 'present',
                'remark' => 'Journal filled',
                'approved_by_teacher' => $journal->approved_by_teacher,
                'approved_by_supervisor' => $journal->approved_by_supervisor,
            ]
        );
    }

    /**
     * Handle the Journal "updated" event.
     */
    public function updated(Journal $journal): void
    {
        // Check if both teacher and supervisor have approved the journal
        if ($journal->approved_by_teacher || $journal->approved_by_supervisor) {
            // Find related attendance record
            $attendance = Attendance::where([
                'student_id' => $journal->student_id,
                'internship_id' => $journal->internship_id,
                'date' => $journal->journal_date,
            ])->first();

            // Update attendance approval
            if ($attendance) {
                $attendance->update([
                    'approved_by_teacher' => $journal->approved_by_teacher,
                'approved_by_supervisor' => $journal->approved_by_supervisor,
                ]);
            }
        }
    }

    /**
     * Handle the Journal "deleted" event.
     */
    public function deleted(Journal $journal): void
    {
        //
    }

    /**
     * Handle the Journal "restored" event.
     */
    public function restored(Journal $journal): void
    {
        //
    }

    /**
     * Handle the Journal "force deleted" event.
     */
    public function forceDeleted(Journal $journal): void
    {
        //
    }
}
