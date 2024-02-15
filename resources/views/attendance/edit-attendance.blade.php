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
                        {{ $e_court->elected_date }},
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
</x-app-layout>
