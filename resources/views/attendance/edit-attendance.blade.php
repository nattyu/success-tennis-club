<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            日程編集フォーム
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6">
        @if (session('message'))
            <div class="text-red-600 font-bold">
                {{ session('message') }}
            </div>
        @endif
        @if (session('error'))
            <div class="text-red-600 font-bold">
                {{ session('error') }}
            </div>
        @endif
        <form id="year_month_select" class="m-4">
            @csrf
            <select name="year_month" id="year_month">
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
        <form method="post" action="{{ route('post-attendance.update', $postAttendance[0]) }}">
            @csrf
            @method('patch')
            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="user_name" class="font-semibold mt-4">回答者 {{ auth()->user()->nickname }}</label>
                </div>
            </div>
            @foreach ($elected_courts as $e_court)
                <div class="mt-8">
                    <label for="court" class="font-semibold mt-2">
                        {{ convertyyyymmddTomd($e_court->elected_date) }},
                        {{ $registed_courts[$e_court->court_id - 1]["court_name"] }}
                        {{ $e_court->court_number }},
                        {{ convertHisToHi($e_court->start_time) }}~{{ convertHisToHi($e_court->end_time) }}
                    </label>
                    @php
                        $filteredAttendance;
                        foreach ($postAttendance as $p_atte) {
                            if ($p_atte['elected_court_id'] === $e_court->id) {
                                $filteredPostAttendance = $p_atte;
                            }
                        }
                    @endphp
                    <select type="text" class="form-control w-16" name="attend_flg[]">
                        <option value="-" @if ($filteredPostAttendance->attend_flg == "-") selected @endif>-</option>
                        <option value="〇" @if ($filteredPostAttendance->attend_flg == "〇") selected @endif>〇</option>
                        <option value="△" @if ($filteredPostAttendance->attend_flg == "△") selected @endif>△</option>
                        <option value="✕" @if ($filteredPostAttendance->attend_flg == "✕") selected @endif>✕</option>
                    </select>
                    <input type="hidden" name="user_id[]" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="attendances[]" value="{{ $e_court->id }}">
                    <input type="hidden" name="attendance_id[]" value="{{ $filteredPostAttendance->id }}">
                </div>
            @endforeach

            <x-primary-button class="mt-4">
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
