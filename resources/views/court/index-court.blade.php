<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            コート一覧
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6">
        @if (session('message'))
            <div class="text-red-600 font-bold">
                {{ session('message') }}
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
        <table class="m-4">
            <tr class="border border-black">
                <th></th>
                @foreach ($postCourts as $p_court)
                    <th class="border border-black p-2">
                        <a href="{{ route('post-court.show', $p_court) }}" class="text-blue-600">
                            {{ $p_court->elected_date }}<br>
                            {{ convertHisToHi($p_court->start_time) }}~{{ convertHisToHi($p_court->end_time) }}<br>
                            {{ $p_court->court->court_name }} {{ $p_court->court_number }}<br>
                            {{ $p_court->user->nickname }}
                        </a>
                    </th>
                @endforeach
            </tr>
            @foreach ($users as $user)
                @if ($user->status != 'exclusion')
                    <tr class="border border-black">
                        <td class="flex items-center py-4">
                            <p class="mx-2">{{ $user->nickname }}</p>
                            @if ($user->id === auth()->user()->id || auth()->user()->role == 'admin')
                                <a href="{{ route('post-attendance.edit', $user->id) }}" class="">
                                    <button class="items-center justify-center">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                </a>
                            @endif
                            @if (auth()->user()->role == 'admin')
                                <form action="{{ route('post-attendance.destroy', $user->id) }}" method="POST" class="mx-2">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button class="items-center justify-center">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                        @foreach ($postCourts as $p_court)
                            @foreach ($attendances as $attendance)
                                @if ($user->id === $attendance->user_id && $p_court->id === $attendance->elected_court_id)
                                    <td class="border border-black text-center">{{ $attendance->attend_flg }}</td>
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                @endif
            @endforeach
        </table>
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
