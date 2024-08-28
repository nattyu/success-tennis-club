<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base sm:text-xl text-gray-800 dark:text-gray-200 leading-tight">
            日程編集フォーム
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 text-sm sm:text-base">
        @if (session('message'))
            <div class="text-red-600 font-bold m-2 sm:m-4">
                {{ session('message') }}
            </div>
        @endif
        @if (session('error'))
            <div class="text-red-600 font-bold m-2 sm:m-4">
                {{ session('error') }}
            </div>
        @endif
        <form id="year_month_select" class="m-2 sm:m-4">
            @csrf
            <select name="year_month" id="year_month" class="text-sm sm:text-base">
                {!! generateYearMonthOptions($select) !!}
            </select>
        </form>
        <form method="post" action="{{ route('post-attendance.update', $postAttendance[0]) }}" class="m-2 sm:m-4">
            @csrf
            @method('patch')
            <div class="">
                <div class="w-full flex flex-col">
                    <label for="user_name" class="font-semibold mt-2 sm:mt-4 dark:text-gray-100">回答者 {{ auth()->user()->nickname }}</label>
                </div>
            </div>
            <table class="mt-2 sm:mt-4 dark:text-gray-100">
                <tr>
                    <td class="border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32">日付</td>
                    <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">当選者</td>
                    <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">コート名</td>
                    <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">コート番号</td>
                    <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">時間</td>
                    <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">出欠</td>
                </tr>
                @foreach ($elected_courts as $e_court)
                    @php
                        $filteredAttendance;
                        foreach ($postAttendance as $p_atte) {
                            if ($p_atte['elected_court_id'] === $e_court->id) {
                                $filteredPostAttendance = $p_atte;
                            }
                        }
                    @endphp
                    <tr>
                        <td class="border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32">{{ convertyyyymmddTomd($e_court->elected_date) }}</td>
                        <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">{{ $e_court->user->nickname }}</td>
                        <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">{{ convertCourtName($registed_courts[$e_court->court_id - 1]["court_name"]) }}</td>
                        <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">{{ $e_court->court_number }}</td>
                        <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">{{ convertHisToHi($e_court->start_time) }}~{{ convertHisToHi($e_court->end_time) }}</td>
                        <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">
                            <select type="text" class="form-control w-16 dark:text-gray-900" name="attend_flg[]">
                                <option value="-" @if ($filteredPostAttendance->attend_flg == "-") selected @endif>-</option>
                                <option value="〇" @if ($filteredPostAttendance->attend_flg == "〇") selected @endif>〇</option>
                                <option value="△" @if ($filteredPostAttendance->attend_flg == "△") selected @endif>△</option>
                                <option value="✕" @if ($filteredPostAttendance->attend_flg == "✕") selected @endif>✕</option>
                            </select>
                        </td>
                    </tr>
                    <input type="hidden" name="elected_date[]" value="{{ $e_court->elected_date }}">
                    <input type="hidden" name="user_id[]" value="{{ $filteredPostAttendance->user_id }}">
                    <input type="hidden" name="attendances[]" value="{{ $e_court->id }}">
                    <input type="hidden" name="attendance_id[]" value="{{ $filteredPostAttendance->id }}">
                @endforeach
            </table>

            <x-primary-button class="my-4">
                更新
            </x-primary-button>
        </form>
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
    </script>
</x-app-layout>
