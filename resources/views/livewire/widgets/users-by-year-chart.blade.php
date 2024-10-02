<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Volt\Component;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

new class extends Component {
    public $usersPerYear = [];

    public function mount()
    {
        // Ambil data pengguna
        $this->usersPerYear = $this->loadUsersData();
    }

    protected function loadUsersData()
    {
        // Ambil pengguna dalam 5 tahun terakhir
        $usersLast5Years = User::where('created_at', '>=', Carbon::now()->subYears(5))->get();

        // Kelompokkan pengguna berdasarkan tahun, lalu hitung jumlah pengguna setiap tahun
        $usersGroupedByYear = $usersLast5Years->groupBy(function ($user) {
            return $user->created_at->format('Y');
        });

        // Ubah koleksi ke array dengan hitungan per tahun
        $usersPerYear = [];
        foreach ($usersGroupedByYear as $year => $users) {
            $usersPerYear[$year] = $users->count();
        }

        return $usersPerYear;
    }

    public function rendering(View $view)
    {
        $columnChartModel = new ColumnChartModel();
        $columnChartModel->setTitle('Pengguna Berdasarkan Tahun');

        // Array warna dari palet Tailwind
        $colors = [
            '#60A5FA', // Sky Blue
            '#FBBF24', // Amber
            '#34D399', // Emerald
            '#F472B6', // Pink
            '#A78BFA', // Purple
        ];

        // Tambahkan data pengguna per tahun ke chart dengan warna berbeda
        foreach ($this->usersPerYear as $year => $userCount) {
            // Ambil warna berdasarkan index tahun
            $color = $colors[array_rand($colors)]; // Menggunakan random color dari array

            $columnChartModel->addColumn($year, $userCount, $color);
        }

        return $view->with([
            'usersChartModel' => $columnChartModel,
        ]);
    }
};
?>

<x-card class="h-full">
    <!-- Render chart -->
    @livewire('livewire-column-chart', ['columnChartModel' => $usersChartModel])
</x-card>
