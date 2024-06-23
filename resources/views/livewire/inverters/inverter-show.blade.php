<div class="text-white lg:p-8 p-2">
    <section>
        <h2 class="text-xl font-semibold">
            {{ __('Status') }}
        </h2>
        <x-inverters.inverter-details :inverter="$this->inverter" class="mt-4" />
    </section>
    <section class="mt-8" x-data="inverterCharts">
        <div class="relative w-full lg:w-2/3">
            <canvas id="inverter-chart"></canvas>
        </div>
    </section>
</div>
