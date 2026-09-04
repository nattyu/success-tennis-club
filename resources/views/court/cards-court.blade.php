<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base sm:text-xl text-gray-800 dark:text-gray-200 leading-tight">
            コート一覧（カード表示）
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 py-6 sm:px-6 sm:py-8">
        @if (session('message'))
            <div class="text-red-600 font-bold mb-4">
                {{ session('message') }}
            </div>
        @endif

        <form id="year_month_select" class="mb-4 flex flex-wrap items-center gap-2">
            @csrf
            <select name="year_month" id="year_month" class="text-sm sm:text-base">
                {!! generateYearMonthOptions($select) !!}
            </select>
            <select name="month_num" id="month_num" class="text-sm sm:text-base">
                {!! generateMonthNumOptions($month_num) !!}
            </select>
        </form>

        @forelse ($postCourts as $p_court)
            <a href="{{ route('post-court.show', ['post_court' => $p_court, 'year_month' => $select, 'month_num' => $month_num]) }}"
                class="flex border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 mb-3">
                <div style="width: 30%;" class="flex flex-col items-center justify-center gap-1 p-3 border-r border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700">
                    <span class="text-lg font-bold dark:text-gray-100">{{ convertyyyymmddTomd($p_court->elected_date) }}</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">({{ getDayOfWeek($p_court->elected_date) }})</span>
                </div>
                <div style="width: 70%;" class="flex flex-col justify-center gap-1 p-3 min-w-0">
                    <span class="font-semibold dark:text-gray-100 truncate">
                        {{ convertCourtName($p_court->court->court_name) }}　{{ convertCourtNumber($p_court->court_number) }}
                    </span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        〇:{{ $attendanceCounts[$p_court->id]['〇'] }}　△:{{ $attendanceCounts[$p_court->id]['△'] }}
                    </span>
                </div>
            </a>
        @empty
            <p class="text-center text-gray-500 dark:text-gray-400 py-8">この期間の日程はありません。</p>
        @endforelse
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ['year_month', 'month_num'].forEach(function(id) {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('change', function() {
                        document.getElementById('year_month_select').submit();
                    });
                }
            });
        });
    </script>
</x-app-layout>
