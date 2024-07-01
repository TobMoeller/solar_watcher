<div class="text-white lg:p-8 p-2">
    <section>
        <h2 class="text-xl font-semibold">
            {{ __('Status') }}
        </h2>
        <x-inverters.inverter-details :inverter="$this->inverter" class="mt-4" wire:poll.300s />
    </section>
    <section class="mt-8">
        <h2 class="text-xl font-semibold mb-4">
            {{ __('History') }}
        </h2>
        @livewire(App\Livewire\Inverters\InverterCharts::class, ['inverter' => $this->inverter])
    </section>
</div>
