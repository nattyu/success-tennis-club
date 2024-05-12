<x-app-layout>
    <!-- バリデーションエラーメッセージを表示 -->
    @if ($errors->any())
        <div class="bg-red-500 text-white p-3 rounded m-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="m-3 flex items-center" action="{{ route('photos.store', $album_id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <label class="py-2 px-4 rounded bg-gray-400 hover:bg-gray-500 text-white text-sm cursor-pointer">
            写真をアップロード
            <input type="file" name="file[]" class="hidden" multiple onchange="this.closest('form').submit()">
            <x-primary-button type="submit" class="hidden">アップロード</x-primary-button>
        </label>
    </form>

    <p class="m-3">アルバム名：{{ $album_name }}</p>

    <ul class="m-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-2 xl:gap-3">
        @foreach ($photos as $photo)
            <li class="group h-32 sm:h-64 flex justify-end items-end bg-gray-100 overflow-hidden rounded-lg shadow-lg relative">
                @if (in_array(strtolower(pathinfo($photo->filename, PATHINFO_EXTENSION)), ['mp4', 'mov', 'avi', 'mkv']))
                    <video
                        src="{{ $photo->photo_url }}"
                        class="w-full h-full object-cover object-center absolute inset-0 group-hover:scale-105 transition duration-200"
                        controls
                    ></video>
                @else
                    <img
                        src="{{ $photo->photo_url }}"
                        alt="Photo by {{ $photo->user->name }}"
                        class="w-full h-full object-cover object-center absolute inset-0 group-hover:scale-105 transition duration-200"
                    />
                @endif
                <form class="hidden group-hover:block" onsubmit="return confirm('本当に削除しますか？')" action="{{ route('photos.destroy', $photo) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="inline-block text-gray-200 text-xs md:text-sm border border-gray-500 rounded-lg backdrop-blur hover:bg-gray-800 relative px-2 md:px-3 py-1 mr-3 mb-3">&times;</button>
                </form>
            </li>
        @endforeach
    </ul>
</x-app-layout>
