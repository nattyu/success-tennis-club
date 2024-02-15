<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            コート詳細
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6">
        <table>
            <tr>
                <td>作成者</td>
                <td>{{ $postCourt->user->nickname }}</td>
            </tr>
            <tr>
                <td>日付</td>
                <td>{{ $postCourt->elected_date }}</td>
            </tr>
            <tr>
                <td>時間</td>
                <td>{{ convertHisToHi($postCourt->start_time) }}~{{ convertHisToHi($postCourt->end_time) }}</td>
            </tr>
            <tr>
                <td>コート</td>
                <td>{{ $postCourt->court->court_name }}</td>
            </tr>
            <tr>
                <td>コート番号</td>
                <td>{{ $postCourt->court_number }}</td>
            </tr>
        </table>
        <a href="{{ route('post-court.edit', $postCourt) }}">
            <x-primary-button class="mt-4">
                編集
            </x-primary-button>
        </a>
        <form action="{{ route('post-court.destroy', $postCourt) }}" method="POST">
            @csrf
            {{ method_field('DELETE') }}
            <x-primary-button class="mt-4 bg-red-600">
                削除
            </x-primary-button>
        </form>
    </div>
</x-app-layout>