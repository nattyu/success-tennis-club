<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base sm:text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ユーザー管理
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 py-6 sm:px-6 sm:py-8">
        @if (session('message'))
            <div class="text-red-600 font-bold mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="space-y-3">
            @foreach ($users as $user)
                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold dark:text-gray-100 truncate">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->nickname }}</p>
                        </div>
                        @if ($user->isApproved())
                            <span class="shrink-0 text-green-600 font-bold text-base sm:text-lg">承認済み</span>
                        @else
                            <span class="shrink-0 text-yellow-600 font-bold text-base sm:text-lg">承認待ち</span>
                        @endif
                    </div>

                    <div class="mt-3 flex items-center gap-2 text-sm dark:text-gray-200">
                        <span class="text-gray-500 dark:text-gray-400 shrink-0">メール</span>
                        <span class="email-mask">••••••••••</span>
                        <span class="email-text hidden break-all">{{ $user->email }}</span>
                        <button type="button" class="email-toggle shrink-0 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="toggleEmail(this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <div class="mt-3 flex items-center gap-2 text-sm">
                        <span class="text-gray-500 dark:text-gray-400 shrink-0">権限</span>
                        @if ($user->id === auth()->id())
                            <span class="dark:text-gray-100">{{ $user->role === 'admin' ? '管理者' : 'メンバー' }}</span>
                        @else
                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="role"
                                    onchange="if (confirm('{{ $user->name }} の権限を変更しますか？')) { this.form.submit(); } else { this.value = '{{ $user->role === 'admin' ? 'admin' : 'member' }}'; }"
                                    class="text-xs sm:text-sm dark:text-gray-900">
                                    <option value="member" @if ($user->role !== 'admin') selected @endif>メンバー</option>
                                    <option value="admin" @if ($user->role === 'admin') selected @endif>管理者</option>
                                </select>
                            </form>
                        @endif
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @if (!$user->isApproved())
                            <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded">
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
                                    class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded">
                                    削除
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function toggleEmail(button) {
            const wrapper = button.closest('div');
            wrapper.querySelector('.email-mask').classList.toggle('hidden');
            wrapper.querySelector('.email-text').classList.toggle('hidden');
            const icon = button.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
</x-app-layout>
