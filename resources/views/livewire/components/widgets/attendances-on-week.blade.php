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
        return $date->isToday() ? 'bg-yellow-50 bg-opacity-70 border border-yellow-500' : ($status === 'hadir' ? 'bg-opacity-50 border border-green-300' : 'bg-opacity-50 border border-gray-300');
    }

    // Set status class based on status
    protected function setStatusClass(string $status): string
    {
        return $status === 'hadir' ? 'text-green-500' : 'text-gray-500';
    }
};
?>

<!-- View with infinite scroll mechanism -->
<div class="flex items-center gap-4">
    <div class="relative flex-1 overflow-x-auto">
        <!-- Fade effect on the left -->
        <div
            class="absolute inset-y-0 left-0 z-10 w-10 pointer-events-none bg-gradient-to-r from-gray-50 to-transparent">
        </div>

        <div id="scroll-container" class="overflow-x-auto whitespace-nowrap" style="scroll-behavior: smooth;"
            wire:target="loadMorePreviousDays" wire:loading.class="opacity-50">

            <div class="inline-flex justify-between gap-4">
                @foreach ($attendances as $attendance)
                    <x-card class="flex items-center justify-between divide-x-2 min-w-16 {{ $attendance['cardClass'] }}">
                        <div class="flex flex-col items-center w-full">
                            <!-- Display date -->
                            <div class="flex items-center gap-1 text-xs">
                                <span class="text-gray-700">{{ $attendance['date']->translatedFormat('D') }}</span>
                                <!-- Display icon based on status -->
                                <iconify-icon class="{{ $attendance['statusClass'] }}"
                                    icon="{{ $attendance['icon'] }}"></iconify-icon>
                            </div>
                            <div class="flex gap-1 text-xs text-gray-400">
                                <span>{{ $attendance['date']->format('d') }}</span>
                                <span>{{ $attendance['date']->translatedFormat('M') }}</span>
                                {{-- <span>{{ $attendance['date']->translatedFormat('Y') }}</span> --}}
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>

        </div>
        <!-- Fade effect on the right -->
        <div
            class="absolute inset-y-0 right-0 z-10 w-10 pointer-events-none bg-gradient-to-l from-gray-50 to-transparent">
        </div>
    </div>

    <div class="min-w-40 max-w-80">
        <x-card class="flex flex-col items-center justify-center h-full font-medium text-center">
            <p>
                <span id="local-time" class="text-2xl text-yellow-500"></span>
                <span id="local-timezone" class="text-xs text-gray-400"></span>
            </p>
            <p class="text-xs text-gray-500">
                <span>{{ Carbon::now()->translatedFormat('d M Y') }}</span>
            </p>
        </x-card>
    </div>
</div>

@script
    <script>
        const date = new Date();

        // Mendapatkan jam dan menit
        const hours = String(date.getHours()).padStart(2, '0'); // Menggunakan padStart untuk 2 digit
        const minutes = String(date.getMinutes()).padStart(2, '0'); // Menggunakan padStart untuk 2 digit

        // Menggabungkan jam dan menit dengan pemisah titik dua
        const localTime = `${hours}:${minutes}`;

        // Mendapatkan hanya nama zona waktu (WIB, WITA, WIT)
        const timeZoneOptions = {
            timeZoneName: 'short'
        };

        // Menampilkan zona waktu
        const localTimeZone = date.toLocaleTimeString('id-ID', timeZoneOptions).split(' ').pop();

        // Menempatkan waktu dan zona waktu di elemen HTML
        document.getElementById('local-time').innerText = localTime;
        document.getElementById('local-timezone').innerText = localTimeZone;

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
