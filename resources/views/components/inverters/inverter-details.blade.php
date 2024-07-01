@props(['attributes', 'inverter'])
<div {{ $attributes->merge(['class' => 'flex flex-col gap-2']) }}>
    <div class="flex flex-row gap-2">
        <div class="w-6 flex justify-center">
            @if($inverter->is_online)
                {{-- heroicons check-circle @TODO install blade-ui-kit --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="h-6 w-6 stroke-green-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            @else
                {{-- heroicons x-circle --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="h-6 w-6 stroke-red-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            @endif
        </div>
        @if($inverter->is_online)
            <div>
                {{ __('Online') }}
            </div>
        @else
            <div class="text-gray-400">
                {{ __('Offline') }}
            </div>
        @endif
    </div>
    <div class="flex flex-col gap-2 sm:flex-row sm:justify-between">
        @if($inverter->outputs->count() > 0)
            <div class="flex flex-row gap-2">
                <div class="w-6 flex justify-center">
                    {{-- heroicons bolt --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="h-5 w-5 stroke-yellow-500">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    @foreach(App\Enums\TimespanUnit::cases() as $unit)
                        @if($output = $inverter->outputs->where('timespan', $unit)->sortByDesc('updated_at')->first()?->output)
                            <div>
                                <span class="text-gray-400">
                                    @php
                                        echo match ($unit) {
                                            App\Enums\TimespanUnit::DAY => 'day:',
                                            App\Enums\TimespanUnit::MONTH => 'month:',
                                            App\Enums\TimespanUnit::YEAR => 'year:',
                                        }
                                    @endphp
                                </span>
                                {{ $output }}
                                <span class="text-gray-400">
                                    kWh
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
        @if($status = $inverter->latestStatus)
            <div class="flex flex-row gap-2">
                <div class="w-6 flex justify-center">
                    {{-- heroicons arrows-up-down --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="h-5 w-5 stroke-purple-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    @foreach(['udc' => 'V', 'idc' => 'A', 'pac' => 'W', 'pdc' => 'W'] as $attribute => $unit)
                        <div class="flex flex-row gap-2">
                            <span class="text-gray-400">{{ Str::upper($attribute) }}:</span>
                            <span>{{ $status->$attribute ?? '0' }}</span>
                            <span class="text-gray-400">{{ $unit }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    <div>
</div>
