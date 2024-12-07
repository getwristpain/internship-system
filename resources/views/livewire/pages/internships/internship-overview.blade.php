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

        dd($this->lastViewedProgram, $this->programs, $this->internships, $this->students, $this->companies);
    }

    private function validateLastViewedProgram()
    {
        $programIdSession = Session::get('last-viewed-program', null);
        $this->lastViewedProgram = ProgramService::getLatestProgram($programIdSession);
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

<div>
    //
</div>
