<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            コート詳細
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6">
        @if (session('message'))
            <div class="text-red-600 font-bold">
                {{ session('message') }}
            </div>
        @endif
        <table class="m-2 sm:m-4">
            <tr>
                <td class="border-t border-b border-gray-500 p-1 sm:p-2 sm:w-32">作成者</td>
                <td class="border-t border-b border-l border-gray-500 p-1 sm:p-2 sm:w-32">{{ $postCourt->user->nickname }}</td>
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