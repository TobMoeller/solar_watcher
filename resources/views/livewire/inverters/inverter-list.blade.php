<div>
    <x-welcome />
    <div>
        <ul class="grid grid-cols-2" wire:poll.300s>
            @foreach ($this->inverters as $inverter)
                <li class="lg:p-8 p-2 text-white border-b odd:border-r border-gray-700 hover:bg-gray-700">
                    <a href="{{ route('guests.inverters.show', ['inverter' => $inverter]) }}">
                        <h2 class="text-lg font-semibold">
                            {{ $inverter->name }}
                        </h2>
                        <div class="flex flex-row gap-2 mt-1">
                            <div>
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
                            <div class="text-gray-400">
                                {{ $inverter->is_online ? __('Online') : __('Offline') }}
                            </div>
                        </div>
                        @if($output = $inverter->outputs->sortByDesc('updated_at')->first()?->output)
                            <div class="flex flex-row gap-2 mt-1">
                                <div>
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
                            <div class="grid grid-cols-2 mt-1">
                                @foreach(['udc', 'idc', 'pac', 'pdc'] as $attribute)
                                    <div class="flex md:flex-row flex-col md:gap-2">
                                        <span>{{ Str::upper($attribute) }}:</span>
                                        <span class="text-gray-400">{{ $status->$attribute ?? '0' }}</span>
                                    </div>
                                @endforeach
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
