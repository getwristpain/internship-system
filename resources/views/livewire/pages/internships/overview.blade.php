<?php

use Carbon\Carbon;
use App\Models\Program;
use Livewire\Volt\Component;
use App\Services\CompanyService;
use App\Services\ProgramService;
use App\Services\InternshipService;
use Livewire\Attributes\{On, Layout};
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Collection;

new #[Layout('layouts.app')] class extends Component {
    public ?Program $lastViewedProgram = null;
    public ?Collection $programs = null;
    public ?Collection $internships = null;
    public ?Collection $students = null;
    public ?Collection $Companies = null;

    public function mount()
    {
        $this->validateLastViewedProgram();
        $this->loadProgramsData();
        $this->loadInternshipsData();
        $this->loadStudentsData();
        $this->loadCompaniesData();
    }

    private function validateLastViewedProgram()
    {
        // Retrieve the last viewed program ID from the session or default to null
        $programIdSession = Session::get('last-viewed-program', null);

        // Get the latest program based on the session ID
        $this->lastViewedProgram = ProgramService::getLatestProgram($programIdSession);

        // Check if the lastViewedProgram is iterable before proceeding
        if ($this->lastViewedProgram && method_exists($this->lastViewedProgram, 'map')) {
            $this->lastViewedProgram->map(function ($program) {
                $program->key = 'program_key' . '_' . Carbon::now() . '_' . $program->id;
            });
        }
    }

    #[On('program-updated')]
    public function handleProgramUpdated()
    {
        $this->reset(['lastViewedProgram']);
        $this->validateLastViewedProgram();
    }

    private function loadProgramsData()
    {
        $this->programs = ProgramService::getAllPrograms();
    }

    private function loadInternshipsData()
    {
        if (!$this->lastViewedProgram) {
            $this->internships = new Collection();
            return;
        }

        $this->internships = InternshipService::getAllInternships($this->lastViewedProgram->id);
    }

    private function loadStudentsData(): void
    {
        if ($this->internships->isEmpty()) {
            $this->students = new Collection();
            return;
        }

        $this->students = $this->internships->flatMap(fn($internship) => $internship->students)->unique('id');
    }

    private function loadCompaniesData()
    {
        $this->companies = CompanyService::getAllCompanies();
    }
}; ?>

<div class="w-full h-full">
    <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
        <div class="col-span-2">
            {{-- Program Details --}}
            @livewire('components.internships.program-detail-card', ['program' => $lastViewedProgram->toArray()], key($lastViewedProgram->key))
            {{-- Program Form Modal --}}
            @livewire('components.internships.program-form-modal')
        </div>
        <div class="col-span-2">
            {{-- Stats Overview --}}
            @livewire('components.internships.internship-stats')
        </div>
        {{-- Student List Table --}}
        {{-- Company List Table --}}
        {{-- Internship Teams --}}
    </div>
</div>
