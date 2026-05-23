<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                日程調整一覧
            </h2>
            <a href="{{ route('post-court.index') }}" class="text-sm text-white bg-gray-500 hover:bg-gray-600 px-3 py-1 rounded-md">
                コート一覧
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6">
        {{ $postAttendance }}
    </div>
</x-app-layout>
