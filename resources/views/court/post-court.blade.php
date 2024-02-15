<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            当選コート投稿フォーム
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6">
        @if (session('message'))
            <div class="text-red-600 font-bold">
                {{ session('message') }}
            </div>
        @endif
        <form method="post" action="{{ route('post-court.store') }}">
            @csrf
            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="elected_member" class="font-semibold mt-4">当選者 {{ auth()->user()->nickname }}</label>
                </div>
            </div>

            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="elected_date" class="font-semibold mt-4">日付</label>
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    <input type="date" class="form-control" id="elected_date" name="elected_date">
                </div>
            </div>

            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="court_id" class="font-semibold mt-4">コート選択</label>
                    <x-input-error :messages="$errors->get('court_id')" class="mt-2" />
                    <select type="text" class="form-control" name="court_id" required>
                        <option disabled style='display:none;' @if (empty($postCourt->court_id)) selected @endif>選択してください</option>
                        @foreach($registed_courts as $r_court)
                            <option value="{{ $r_court->id }}" @if (isset($r_court->court_id) && ($r_court->court_id === $postCourt->court_id)) selected @endif>{{ $r_court->court_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="court_number" class="font-semibold mt-4">コート番号</label>
                    <x-input-error :messages="$errors->get('court_number')" class="mt-2" />
                    <input type="text" name="court_number" class="w-auto py-2 border border-gray-300 rounded-md" id="court_number" value="{{ old('court_number') }}">
                </div>
            </div>

            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="start_time" class="font-semibold mt-4">開始時間</label>
                    <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                    <select type="text" class="form-control" name="start_time" required>
                        <option disabled style='display:none;' @if (empty($postCourt->start_time)) selected @endif>選択してください</option>
                        @foreach($start_times as $s_time)
                            <option value="{{ $s_time }}">{{ $s_time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="end_time" class="font-semibold mt-4">終了時間</label>
                    <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                    <select type="text" class="form-control" name="end_time" required>
                        <option disabled style='display:none;' @if (empty($postCourt->end_time)) selected @endif>選択してください</option>
                        @foreach($end_times as $e_time)
                            <option value="{{ $e_time }}">{{ $e_time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <x-primary-button class="mt-4">
                登録
            </x-primary-button>
        </form>
    </div>
</x-app-layout>
