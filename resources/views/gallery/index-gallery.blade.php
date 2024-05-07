<x-app-layout>
    <form class="m-3 sm:flex items-center" action="{{ route('gallery.store') }}" method="post">
        @csrf
        <p class="mt-2 text-sm">アルバムを作成</p>
        <input type="text" class="mt-2 sm:mx-2" name="album_name" multiple onchange="this.closest('form').submit()" placeholder="アルバム名を入力">
        <x-primary-button type="submit" class="mt-2">作成</x-primary-button>
    </form>
    <ul class="m-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-2 xl:gap-3">
        @foreach ($albums as $album)
            <a href="{{ route('photos.index', $album->id) }}">
                <li class="group h-32 sm:h-64 flex justify-between bg-gray-100 overflow-hidden rounded-lg shadow-lg relative">
                    <p class="px-2 md:px-3 py-1 mr-3 mb-3">{{ $album->album_name }}</p>
                    <form class="block" onsubmit="return confirm('本当に削除しますか？')" action="{{ route('gallery.destroy', $album) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="inline-block text-gray-600 text-xs md:text-sm border border-gray-600 rounded-lg backdrop-blur hover:bg-gray-800 relative px-2 md:px-3 py-1 mr-3 mb-3">&times;</button>
                    </form>
                </li>
            </a> 
        @endforeach
    </ul>
</x-app-layout>