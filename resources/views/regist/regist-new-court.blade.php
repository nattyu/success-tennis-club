<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base sm:text-xl text-gray-800 dark:text-gray-200 leading-tight">
            コート新規登録フォーム
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 text-sm sm:text-base">
        @if (session('message'))
            <div class="text-red-600 font-bold m-2 sm:m-4">
                {{ session('message') }}
            </div>
        @endif
        <form method="post" action="{{ route('regist-new-court.store') }}" class="m-2 sm:m-4">
            @csrf
            <div class="my-2 sm:my-4">
                <div class="w-full sm:w-[50%] flex flex-col">
                    <label for="title" class="font-semibold mt-4">コート名</label>
                    <x-input-error :messages="$errors->get('court_name')" class="mt-2" />
                    <input type="text" name="court_name" class="w-auto py-2 border border-gray-300 rounded-md" id="court_name" value="{{ old('court_name') }}">
                </div>
            </div>

            <div class="my-2 sm:my-4">
                <div class="w-full sm:w-[50%] flex flex-col">
                    <label for="title" class="font-semibold mt-4">住所</label>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    <input type="text" name="address" class="w-auto py-2 border border-gray-300 rounded-md" id="address" value="{{ old('address') }}">
                </div>
            </div>

            <x-primary-button class="my-2 sm:my-4">
                登録
            </x-primary-button>
        </form>
    </div>
</x-app-layout>
