<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            コート詳細
        </h2>
    </x-slot>

    <div class="sm-flex">
        <div class="max-w-7xl mx-auto px-6">
            @if (session('message'))
                <div class="text-red-600 font-bold">
                    {{ session('message') }}
                </div>
            @endif
            <div class="flex items-center">
                @if (isset($previousCourt))
                    <a href="{{ route('post-court.show', $previousCourt) }}" class="">
                        <svg version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="width: 32px; height: 32px; opacity: 1;" xml:space="preserve">
                            <style type="text/css">
                                .st0{fill:#4B4B4B;}
                            </style>
                            <g>
                                <polygon class="st0" points="277.919,132.921 154.839,256 277.919,379.072 318.552,338.438 236.122,256 318.552,173.562 	" style="fill: rgb(75, 75, 75);"></polygon>
                                <path class="st0" d="M256.008,0C114.605,0.016,0.016,114.606,0,256c0.015,141.394,114.605,255.984,256.008,256
                                    C397.394,511.984,511.983,397.394,512,256C511.983,114.606,397.394,0.016,256.008,0z M408.585,408.585
                                    c-39.11,39.079-92.93,63.189-152.577,63.205c-59.655-0.016-113.483-24.126-152.594-63.205C64.328,369.475,40.217,315.647,40.21,256
                                    c0.007-59.654,24.118-113.474,63.204-152.585c39.111-39.086,92.939-63.197,152.594-63.205
                                    c59.646,0.008,113.466,24.119,152.577,63.205c39.079,39.11,63.197,92.93,63.205,152.585
                                    C471.782,315.647,447.664,369.475,408.585,408.585z" style="fill: rgb(75, 75, 75);"></path>
                            </g>
                        </svg>
                    </a>
                @endif
                <table class="m-2 sm:m-4 dark:text-gray-100">
                    <tr>
                        <td class="border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32">作成者</td>
                        <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">
                            @if (isset($postCourt->user->nickname))
                                {{ $postCourt->user->nickname }}
                            @else
                                unknown
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32">日付</td>
                        <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">{{ convertyyyymmddTomd($postCourt->elected_date) }}</td>
                    </tr>
                    <tr>
                        <td class="border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32">時間</td>
                        <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">{{ convertHisToHi($postCourt->start_time) }}~{{ convertHisToHi($postCourt->end_time) }}</td>
                    </tr>
                    <tr>
                        <td class="border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32">コート</td>
                        <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">{{ $postCourt->court->court_name }}</td>
                    </tr>
                    <tr>
                        <td class="border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32">コート番号</td>
                        <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">{{ $postCourt->court_number }}</td>
                    </tr>
                </table>
                @if (isset($nextCourt))
                    <a href="{{ route('post-court.show', $nextCourt) }}" class="my-auto">
                        <svg version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="width: 32px; height: 32px; opacity: 1;" xml:space="preserve">
                            <style type="text/css">
                                .st0{fill:#4B4B4B;}
                            </style>
                            <g>
                                <polygon class="st0" points="193.447,173.562 275.877,256 193.447,338.438 234.081,379.08 357.161,256 234.081,132.928 	" style="fill: rgb(75, 75, 75);"></polygon>
                                <path class="st0" d="M255.992,0C114.606,0.015,0.015,114.606,0,256c0.015,141.394,114.606,255.984,255.992,256
                                    C397.394,511.984,511.985,397.394,512,256C511.985,114.606,397.394,0.015,255.992,0z M408.585,408.585
                                    c-39.118,39.079-92.938,63.189-152.593,63.205c-59.647-0.016-113.467-24.126-152.577-63.205
                                    C64.328,369.474,40.218,315.647,40.21,256c0.008-59.655,24.118-113.475,63.205-152.585c39.11-39.087,92.93-63.197,152.577-63.205
                                    c59.655,0.008,113.476,24.118,152.593,63.205c39.087,39.11,63.197,92.93,63.205,152.585
                                    C471.782,315.647,447.672,369.474,408.585,408.585z" style="fill: rgb(75, 75, 75);"></path>
                            </g>
                        </svg>
                    </a>
                @endif
            </div>
            <div class="m-2 sm:m-4 flex">
                <a href="{{ route('post-court.edit', $postCourt) }}">
                    <x-primary-button class="mt-4">
                        編集
                    </x-primary-button>
                </a>
                <form id="delete-form-{{ $postCourt->id }}" action="{{ route('post-court.destroy', $postCourt) }}" method="POST" class="ml-4">
                    @csrf
                    {{ method_field('DELETE') }}
                    <x-primary-button type="button" class="mt-4 bg-red-600" onclick="confirmDelete({{ $postCourt->id }})">
                        削除
                    </x-primary-button>
                </form>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 dark:text-gray-100">
            <p class="py-4">参加者予定者一覧</p>
            <div class="grid grid-cols-2 gap-2">
                <div class="border-l border-t border-r border-gray-500 p-2 text-center">
                    〇：{{ count($attendance_OK_member) }}人
                </div>
                <div class="border-t border-r border-gray-500 p-2 text-center">
                    △：{{ count($attendance_Yet_member) }}人
                </div>
                <div class="border border-gray-500 p-2 text-center">
                    @foreach ($attendance_OK_member as $OK)
                        @if (isset($OK->user->nickname))
                            <p>{{ $OK->user->nickname }}</p>
                        @else
                            unknown
                        @endif
                    @endforeach
                </div>
                <div class="border-b border-t border-r border-gray-500 p-2 text-center">
                    @foreach ($attendance_Yet_member as $Yet)
                        @if (isset($Yet->user->nickname))
                            <p>{{ $Yet->user->nickname }}</p>
                        @else
                            unknown
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(courtId) {
            if (confirm('本当に削除しますか？')) {
                document.getElementById('delete-form-' + courtId).submit();
            } else {
                // 今いるページにリダイレクトさせる
                window.location.href = "{{ url()->current() }}";
                confirm('削除しませんでした');
            }
        }
    </script>
</x-app-layout>