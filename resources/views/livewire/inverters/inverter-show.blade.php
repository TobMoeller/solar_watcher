<div class="text-white lg:p-8 p-2">
    <section>
        <h2 class="text-xl font-semibold">
            {{ __('Status') }}
        </h2>
        <x-inverters.inverter-details :inverter="$this->inverter" class="mt-4" />
    </section>
    <section class="mt-8" x-data="inverterCharts">
        <div>
            <ul class="flex flex-row gap-2 flex-wrap">
                @foreach($this->selectableYears as $year)
                    <li>
                        <button @click="setYear(@js($year))" :class="isYearSelected(@js($year)) ? 'text-white' : 'text-gray-400'">
                            {{ $year }}
                        </button>
                    </li>
                @endforeach
            </ul>
            @if($months = $this->selectableMonths)
                <ul class="flex flex-row gap-1 mt-2 flex-wrap">
                    @foreach($months as $month)
                        <li>
                            <button @click="setMonth(@js($month))" :class="isMonthSelected(@js($month)) ? 'text-white' : 'text-gray-400'">
                                {{ $carbon = Illuminate\Support\Carbon::create($this->selectedYear, $month)->locale('EN_en')->monthName }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div x-show="error !== null" x-text="error" class="mt-4 text-red-400"></div>
        <div x-show="error === null" class="relative w-full lg:w-2/3 mt-4">
            <canvas id="inverter-chart"></canvas>
        </div>
    </section>
</div>
