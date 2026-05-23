<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base sm:text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ユーザー管理
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-8">
        @if (session('message'))
            <div class="text-red-600 font-bold mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="overflow-auto">
            <table class="w-full border-collapse text-sm sm:text-base">
                <thead>
                    <tr class="bg-gray-300 dark:bg-gray-700">
                        <th class="border border-gray-400 p-2 text-left">名前</th>
                        <th class="border border-gray-400 p-2 text-left">ニックネーム</th>
                        <th class="border border-gray-400 p-2 text-left">メールアドレス</th>
                        <th class="border border-gray-400 p-2 text-center">権限</th>
                        <th class="border border-gray-400 p-2 text-center">承認状態</th>
                        <th class="border border-gray-400 p-2 text-center">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b border-gray-300 dark:border-gray-600">
                            <td class="border border-gray-300 p-2 dark:text-gray-100">{{ $user->name }}</td>
                            <td class="border border-gray-300 p-2 dark:text-gray-100">{{ $user->nickname }}</td>
                            <td class="border border-gray-300 p-2 dark:text-gray-100">{{ $user->email }}</td>
                            <td class="border border-gray-300 p-2 text-center dark:text-gray-100">
                                {{ $user->role === 'admin' ? '管理者' : 'メンバー' }}
                            </td>
                            <td class="border border-gray-300 p-2 text-center">
                                @if ($user->isApproved())
                                    <span class="text-green-600 font-bold">承認済み</span>
                                @else
                                    <span class="text-yellow-600 font-bold">承認待ち</span>
                                @endif
                            </td>
                            <td class="border border-gray-300 p-2 text-center">
                                <div class="flex justify-center gap-2 flex-wrap">
                                    @if (!$user->isApproved())
                                        <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">
                                                承認
                                            </button>
                                        </form>
                                    @endif
                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('{{ $user->name }} を削除しますか？\n関連する出欠データも削除されます。')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded">
                                                削除
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
