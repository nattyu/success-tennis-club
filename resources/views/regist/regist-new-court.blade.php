<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            コート新規登録フォーム
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6">
        @if (session('message'))
            <div class="text-red-600 font-bold">
                {{ session('message') }}
            </div>
        @endif
        <form method="post" action="{{ route('regist-new-court.store') }}">
            @csrf
            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="title" class="font-semibold mt-4">コート名</label>
                    <x-input-error :messages="$errors->get('court_name')" class="mt-2" />
                    <input type="text" name="court_name" class="w-auto py-2 border border-gray-300 rounded-md" id="court_name" value="{{ old('court_name') }}">
                </div>
            </div>

            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="title" class="font-semibold mt-4">住所</label>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    <input type="text" name="address" class="w-auto py-2 border border-gray-300 rounded-md" id="address" value="{{ old('address') }}">
                </div>
            </div>

            <x-primary-button class="mt-4">
                登録
            </x-primary-button>
        </form>
    </div>
</x-app-layout>
