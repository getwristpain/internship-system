<?php

use Livewire\Volt\Component;
use Carbon\Carbon;

new class extends Component {
    public array $attendances = []; // Array to store attendance data
    public Carbon $today;
    public int $loadDays = 12; // Number of days displayed before today
    public int $daysAfter = 4; // Number of days displayed after today
    public Carbon $minDateLimit; // Minimum date limit (January 1, 2024)

    public function mount()
    {
        $this->minDateLimit = Carbon::create(2024, 1, 1); // Set minimum date to January 1, 2024
        $this->getDates();
    }

    // Function to get past and future days' attendance data
    protected function getDates()
    {
        $this->today = Carbon::today();
        $this->loadPreviousDays();
        $this->loadFutureDays();
    }

    // Load past attendance data
    protected function loadPreviousDays()
    {
        for ($i = -$this->loadDays; $i <= 0; $i++) {
            $date = $this->today->copy()->addDays($i);
            if ($date->lessThan($this->minDateLimit)) {
                break;
            }
            $this->attendances[] = $this->createAttendanceData($date);
        }
    }

    // Load future attendance data
    protected function loadFutureDays()
    {
        for ($i = 1; $i <= $this->daysAfter; $i++) {
            $date = $this->today->copy()->addDays($i);
            $this->attendances[] = $this->createAttendanceData($date);
        }
    }

    // Create attendance data based on the date
    protected function createAttendanceData(Carbon $date): array
    {
        $status = $this->setStatus($date);
        return [
            'date' => $date,
            'status' => $status,
            'icon' => $this->setIcon($status),
            'cardClass' => $this->setCardClass($date, $status),
            'statusClass' => $this->setStatusClass($status),
        ];
    }

    // Load more previous days' attendance data
    public function loadMorePreviousDays()
    {
        $firstDate = Carbon::parse($this->attendances[0]['date']);

        for ($i = 1; $i <= $this->loadDays; $i++) {
            $date = $firstDate->copy()->subDays($i);
            if ($date->lessThan($this->minDateLimit)) {
                break;
            }
            array_unshift($this->attendances, $this->createAttendanceData($date));
        }
    }

    // Determine attendance status
    protected function setStatus(Carbon $date): string
    {
        return $date->isPast() ? 'hadir' : 'tidak hadir';
    }

    // Get icon based on status
    protected function setIcon(string $status): string
    {
        return $status === 'hadir' ? 'icon-park-outline:check-one' : 'tabler:clock';
    }

    // Set card class based on date and status
    protected function setCardClass(Carbon $date, string $status): string
    {
        return $date->isToday() ? 'bg-yellow-50 border border-yellow-500' : ($status === 'hadir' ? 'border border-green-300' : 'border border-gray-300');
    }

    // Set status class based on status
    protected function setStatusClass(string $status): string
    {
        return $status === 'hadir' ? 'text-green-500' : 'text-gray-500';
    }
};
?>

<!-- View with infinite scroll mechanism -->
<div id="scroll-container" class="overflow-x-auto whitespace-nowrap" style="scroll-behavior: smooth;"
    wire:target="loadMorePreviousDays" wire:loading.class="opacity-50">
    <div class="inline-flex space-x-4">
        @foreach ($attendances as $attendance)
            <x-card class="flex items-center justify-between divide-x-2 min-w-[100px] {{ $attendance['cardClass'] }}">
                <div class="flex flex-col items-center w-full">
                    <!-- Display date -->
                    <span class="text-4xl">{{ $attendance['date']->format('d') }}</span>
                    <div class="flex items-center gap-1 text-sm">
                        <!-- Display icon based on status -->
                        <iconify-icon class="{{ $attendance['statusClass'] }}"
                            icon="{{ $attendance['icon'] }}"></iconify-icon>
                        <span class="text-gray-700">{{ $attendance['date']->translatedFormat('D') }}</span>
                    </div>
                    <div class="flex gap-1 text-xs text-gray-400">
                        <span>{{ $attendance['date']->translatedFormat('M') }}</span>
                        <span>{{ $attendance['date']->translatedFormat('Y') }}</span>
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>
</div>

@script
    <script>
        let isLoading = false; // Track loading state
        const loadMore = () => {
            if (!isLoading) {
                isLoading = true;
                @this.call('loadMorePreviousDays').then(() => {
                    setTimeout(() => {
                        isLoading = false; // Reset loading state after 1 second
                    }, 1000); // 1 second delay
                });
            }
        };

        document.getElementById('scroll-container').addEventListener('scroll', function() {
            if (this.scrollLeft <= 50) {
                loadMore(); // Call the loadMore function
            }
        });
    </script>
@endscript
