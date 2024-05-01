<x-app-layout>
    <form class="m-3 flex items-center" action="{{ route('photos.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label class="py-2 px-4 rounded bg-gray-400 hover:bg-gray-500 text-white text-sm cursor-pointer">
            写真をアップロード
            <input type="file" name="file[]" class="hidden" multiple onchange="this.closest('form').submit()">
            <x-primary-button type="submit" class="hidden">アップロード</x-primary-button>
        </label>
    </form>
    <ul class="m-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-2 xl:gap-3">
        @foreach ($photos as $photo)
            <li class="group h-32 sm:h-64 flex justify-end items-end bg-gray-100 overflow-hidden rounded-lg shadow-lg relative">
                <img
                    src="{{ $photo->photo_url }}"
                    alt="Photo by {{ $photo->user->name }}"
                    class="w-full h-full object-cover object-center absolute inset-0 group-hover:scale-105 transition duration-200"
                />
                <div class="bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-50 absolute inset-0 pointer-events-none"></div>
                <form class="hidden group-hover:block" onsubmit="return confirm('本当に削除しますか？')" action="{{ route('photos.destroy', $photo) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="inline-block text-gray-200 text-xs md:text-sm border border-gray-500 rounded-lg backdrop-blur hover:bg-gray-800 relative px-2 md:px-3 py-1 mr-3 mb-3">&times;</button>
                </form>
            </li>
        @endforeach
    </ul>
</x-app-layout>