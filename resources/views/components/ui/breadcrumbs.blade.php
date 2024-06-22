<nav id="breadcrumb" class="mt-2">
    <ul class="flex flex-row gap-3 flex-wrap">
        @foreach(App\Services\Breadcrumbs\Breadcrumbs::all() as $breadcrumb)
            <li class="group">
                @if($route = $breadcrumb->route)
                    <a href="{{ $route }}">
                @endif
                @if(($label = $breadcrumb->label) instanceof Illuminate\Contracts\Support\Htmlable)
                    {!! $label !!}
                @else
                    <div class="flex flex-row gap-1 items-center flex-nowrap">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                               class="{{ $breadcrumb->active ? 'stroke-yellow-500' : 'stroke-white' }} w-4 h-4 group-hover:stroke-yellow-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                        <span class="{{ $breadcrumb->active ? 'text-white' : 'text-gray-400'}} group-hover:text-white font-thin">
                            {{ $label }}
                        </span>
                    </div>
                @endif
                @if($route)
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
