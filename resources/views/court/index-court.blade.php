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
                <option value="3" {{ $select == '3' ? 'selected': '' }}>2024年3月</option>
                <option value="4" {{ $select == '4' ? 'selected': '' }}>2024年4月</option>
                <option value="5" {{ $select == '5' ? 'selected': '' }}>2024年5月</option>
                <option value="6" {{ $select == '6' ? 'selected': '' }}>2024年6月</option>
                <option value="7" {{ $select == '7' ? 'selected': '' }}>2024年7月</option>
                <option value="8" {{ $select == '8' ? 'selected': '' }}>2024年8月</option>
                <option value="9" {{ $select == '9' ? 'selected': '' }}>2024年9月</option>
                <option value="10" {{ $select == '10' ? 'selected': '' }}>2024年10月</option>
                <option value="11" {{ $select == '11' ? 'selected': '' }}>2024年11月</option>
                <option value="12" {{ $select == '12' ? 'selected': '' }}>2024年12月</option>
            </select>
        </form>
        <div class="whitespace-nowrap overflow-auto w-[95%] top-0">
            <table class="m-2 sm:m-4 border-collapse text-sm sm:text-base">
                <tr class="sticky top-0 bg-gray-300">
                    <th class="sticky left-0 border-t border-b border-gray-500 bg-gray-300">
                        日付<br>
                        時間<br>
                        コート名<br>
                        コート番号<br>
                        当選者
                    </th>
                    @foreach ($postCourts as $p_court)
                        <th class="border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32">
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
                @foreach ($users as $user)
                    @if ($user->status != 'exclusion')
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
                                @php
                                    $attend_array = $attendances->where('user_id', $user->id)->where('elected_court_id', $p_court->id)->values();
                                    $attend_dict = $attend_array[0];
                                    $attend_flg = $attend_dict['attend_flg'];
                                @endphp
                                <td class="border-b border-gray-500 text-center p-1 sm:p-2 sm:w-32 dark:text-gray-100">{{ $attend_flg }}</td>
                            @endforeach
                        </tr>
                    @endif
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
