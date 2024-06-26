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
                @foreach($this->getYears() as $year)
                    <li>
                        <button @click="setYear(@js($year))" :class="isYearSelected(@js($year)) ? 'text-white' : 'text-gray-400'">
                            {{ $year }}
                        </button>
                    </li>
                @endforeach
            </ul>
            @foreach($this->getYears() as $year)
                <ul x-cloak x-show="isYearSelected(@js($year))" class="flex flex-row gap-1 mt-2 flex-wrap">
                    @foreach($this->getMonths($year) as $month)
                        <li>
                            <button @click="setMonth(@js($year), @js($month))" :class="isMonthSelected(@js($year), @js($month)) ? 'text-white' : 'text-gray-400'">
                                {{ $carbon = Illuminate\Support\Carbon::create($year, $month)->locale('EN_en')->monthName }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
        <div class="relative w-full lg:w-2/3 mt-4">
            <canvas id="inverter-chart"></canvas>
        </div>
    </section>
</div>
