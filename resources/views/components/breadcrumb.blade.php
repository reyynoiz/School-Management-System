@props(['items'])

<nav class="flex mb-4" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-1 text-sm text-gray-500">
        <li>
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Home</a>
        </li>
        @foreach ($items as $label => $url)
            <li class="flex items-center space-x-1">
                <span>/</span>
                @if ($url && !$loop->last)
                    <a href="{{ $url }}" class="hover:text-indigo-600">{{ $label }}</a>
                @else
                    <span class="text-gray-700 font-medium">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>