<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            日程調整一覧
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6">
        {{ $postAttendance }}
    </div>
</x-app-layout>
