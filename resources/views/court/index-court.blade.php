<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base sm:text-xl text-gray-800 dark:text-gray-200 leading-tight">
            コート一覧
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6">
        @if (session('message'))
            <div class="text-red-600 font-bold m-2 sm:m-4">
                {{ session('message') }}
            </div>
        @endif
        <form id="year_month_select" class="m-2 sm:m-4">
            @csrf
            <select name="year_month" id="year_month" class="text-sm sm:text-base">
                {!! generateYearMonthOptions($select) !!}
            </select>
        </form>
        <div class="whitespace-nowrap overflow-auto w-[95%] h-full">
            <table class="m-2 sm:m-4 border-collapse text-sm sm:text-base">
                <tr id="row-1" class="">
                    <th class="sticky top-0 left-0 z-10 p-1 sm:p-2 sm:w-32 border-t border-b border-gray-500 bg-gray-300">
                        日付<br>
                        時間<br>
                        コート名<br>
                        コート番号<br>
                        当選者
                    </th>
                    @foreach ($postCourts as $p_court)
                        <th class="sticky top-0 z-0 border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32 bg-gray-300">
                            <a href="{{ route('post-court.show', $p_court) }}" class="text-blue-600">
                                {{ convertyyyymmddTomd($p_court->elected_date) }} ({{ getDayOfWeek($p_court->elected_date)}})<br>
                                {{ convertHisToHi($p_court->start_time) }}~{{ convertHisToHi($p_court->end_time) }}<br>
                                {{ convertCourtName($p_court->court->court_name) }}<br>
                                {{ convertCourtNumber($p_court->court_number) }}<br>
                                @if (isset($p_court->user->nickname))
                                    {{ $p_court->user->nickname }}
                                @else
                                    unknown
                                @endif
                            </a>
                        </th>
                    @endforeach
                </tr>
                <tr id="row-2">
                    <td class="sticky left-0 border-t border-b border-gray-500 bg-gray-300">
                        参加人数
                    </td>
                    @foreach ($postCourts as $p_court)
                        <td class="border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32 text-center dark:text-gray-100">
                            〇:{{ $attendanceCounts[$p_court->id]['〇'] }},
                            △:{{ $attendanceCounts[$p_court->id]['△'] }}
                        </td>
                    @endforeach
                </tr>
                @foreach ($users as $user)
                    <tr class="">
                        <td class="py-3 sm:py-4 sticky left-0 border-t border-b border-gray-500 bg-gray-300">
                            <div class="flex flex-wrap items-center">
                                <p class="mx-1">{{ $user->nickname }}</p>
                                @if ($user->id === auth()->user()->id || auth()->user()->role == 'admin')
                                    <a href="{{ route('post-attendance.edit', $user->id) }}" class="mx-1">
                                        <button class="items-center justify-center">
                                            <i class="fa-solid fa-pencil"></i>
                                        </button>
                                    </a>
                                @endif
                                @if (auth()->user()->role == 'admin')
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('post-attendance.destroy', $user->id) }}" method="POST" class="mx-1">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button type="button" class="items-center justify-center" onclick="confirmDelete({{ $user->id }})">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        @foreach ($postCourts as $p_court)
                            <td class="border-b border-gray-500 text-center p-1 sm:p-2 sm:w-32 dark:text-gray-100">
                                {{ $attendanceMatrix[$user->id][$p_court->id] }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const year_month_Input = document.getElementById('year_month');
            if (year_month_Input) {
                year_month_Input.addEventListener('change', function() {
                    document.getElementById('year_month_select').submit();
                });
            }
        });

        function confirmDelete(userId) {
            if (confirm('本当に削除しますか？')) {
                document.getElementById('delete-form-' + userId).submit();
            } else {
                // 今いるページにリダイレクトさせる
                window.location.href = "{{ url()->current() }}";
                confirm('削除しませんでした');
            }
        }
    </script>
</x-app-layout>
