@props(['layout', 'header', 'outputDay', 'outputMonth', 'outputYear'])
<x-dynamic-component :component="$layout" >
    <x-slot name="header">
        {{ $header }}
    </x-slot>
    <div class="text-white lg:p-8 p-2">
        <section>
            <h2 class="text-xl font-semibold mb-4">
                {{ __('Status') }}
            </h2>
            <div class="flex flex-row gap-2">
                <div class="w-6 flex justify-center">
                    {{-- heroicons bolt --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="h-5 w-5 stroke-yellow-500">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    @foreach(['outputDay', 'outputMonth', 'outputYear'] as $output)
                        @if($$output)
                            <div>
                                <span class="text-gray-400">
                                    @php
                                        echo match ($output) {
                                            'outputDay' => 'day:',
                                            'outputMonth' => 'month:',
                                            'outputYear' => 'year:',
                                        }
                                    @endphp
                                </span>
                                {{ $$output }}
                                <span class="text-gray-400">
                                    kWh
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
        <section class="mt-8">
            <h2 class="text-xl font-semibold mb-4">
                {{ __('History') }}
            </h2>
            @livewire(App\Livewire\Inverters\InverterCharts::class)
        </section>
    </div>
</x-dynamic-component>

