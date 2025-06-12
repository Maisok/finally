@props(['paginator'])

@if ($paginator->hasPages())
    <div class="mt-6 flex items-center justify-between">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-[#3C3C3C] rounded-lg cursor-not-allowed">
                ← Предыдущая
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#3C3C3C] hover:bg-gray-600 rounded-lg transition duration-150 ease-in-out">
                ← Предыдущая
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#3C3C3C] hover:bg-gray-600 rounded-lg transition duration-150 ease-in-out">
                Следующая →
            </a>
        @else
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-[#2A2A2A] rounded-lg cursor-not-allowed">
                Следующая →
            </span>
        @endif
    </div>

    <!-- Номер страницы -->
    <div class="mt-4 text-sm text-gray-400 text-center">
        Показано {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} из {{ $paginator->total() }} записей
    </div>
@endif