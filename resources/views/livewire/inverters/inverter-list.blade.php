<div>
    <x-welcome />
    <div>
        <ul class="grid grid-cols-2" wire:poll.300s>
            <li class="lg:p-4 p-2 text-white border-b border-gray-700 hover:bg-gray-700 col-span-2">
                <a href="{{ route('guests.inverters.show.combined') }}">
                    <h2 class="text-lg font-semibold text-center">
                        {{ __('Combined') }}
                    </h2>
                </a>
            </li>
            @foreach ($this->inverters as $inverter)
                <li class="lg:p-8 p-2 text-white border-b sm:even:border-r border-gray-700 hover:bg-gray-700 col-span-2 sm:col-span-1">
                    <a href="{{ route('guests.inverters.show', ['inverter' => $inverter]) }}">
                        <h2 class="text-lg font-semibold">
                            {{ $inverter->name }}
                        </h2>
                        <div class="flex flex-row gap-2 mt-1">
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
                        @if($output = $inverter->outputs->sortByDesc('updated_at')->first()?->output)
                            <div class="flex flex-row gap-2 mt-1">
                                <div class="w-6 flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="h-5 w-5 stroke-yellow-500">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                                    </svg>
                                </div>
                                <div>
                                    {{ $output }}
                                    <span class="text-gray-400">
                                        kWh
                                    </span>
                                </div>
                            </div>
                        @endif
                        @if($status = $inverter->latestStatus)
                        <div class="flex flex-row gap-2 mt-1">
                            <div class="w-6 flex justify-center">
                                {{-- heroicons arrows-up-down --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="h-5 w-5 stroke-purple-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                </svg>
                            </div>
                            <div class="grid grid-cols-2 w-full">
                                @foreach(['udc' => 'V', 'idc' => 'A', 'pac' => 'W', 'pdc' => 'W'] as $attribute => $unit)
                                    <div class="flex md:flex-row flex-col md:gap-2">
                                        <span class="text-gray-400">{{ Str::upper($attribute) }}:</span>
                                        <span>{{ $status->$attribute ?? '0' }}</span>
                                        <span class="text-gray-400 hidden md:block">{{ $unit }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="md:px-8 p-2">
        {{ $this->inverters->links() }}
    </div>
</div>
