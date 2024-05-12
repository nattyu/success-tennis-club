<x-app-layout>
    <form class="m-3 sm:flex items-center" action="{{ route('gallery.store') }}" method="post">
        @csrf
        <p class="mt-2 text-sm dark:text-gray-100">アルバムを作成</p>
        <input type="text" class="mt-2 sm:mx-2" name="album_name" multiple placeholder="アルバム名を入力">
        <x-primary-button type="submit" class="mt-2">作成</x-primary-button>
    </form>
    <ul class="m-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-2 xl:gap-3">
        @foreach ($albums as $album)
            <a href="{{ route('photos.index', $album->id) }}">
                <li class="group h-32 sm:h-64 overflow-hidden rounded-lg shadow-lg relative">
                    <div class="flex justify-items-center justify-between items-center bg-gray-100 h-[20%]">
                        <p class="px-2 md:px-3 py-1 mr-3 mb-3">{{ $album->album_name }}</p>
                        <form class="block" onsubmit="return confirm('本当に削除しますか？')" action="{{ route('gallery.destroy', $album) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="inline-block text-gray-600 text-xs md:text-sm border border-gray-600 rounded-lg backdrop-blur hover:bg-gray-800 relative px-2 md:px-3 py-1 mr-3 mb-3">&times;</button>
                        </form>
                    </div>
                    <div class="bg-gray-200 h-[80%]">
                        @if($album->photo)
                            <img src="{{ $album->photo->photo_url }}" alt="Photo from album {{ $album->album_name }}" class="object-cover w-full h-full">
                        @else
                            <p class="text-white text-center py-12">No Image Available</p>
                        @endif
                    </div>
                </li>
            </a> 
        @endforeach
    </ul>
</x-app-layout>