@extends('dashboard.layouts.main')

@section('container')
<div class="p-10 sm:ml-80">
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-5xl mx-auto mt-20">

        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Edit Sponsor</h1>
        </div>

        @if(session()->has('success'))
        <div class="flex items-center p-4 mt-4 mb-6 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            {{ session('success') }}
            <button type="button" class="ml-auto text-green-800 hover:text-green-900">&times;</button>
        </div>
        @endif

        <form method="post" action="/dashboard/sponsors/{{ $sponsor->slug }}" enctype="multipart/form-data" class="mt-6">
            @method('put')
            @csrf

            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-6 py-6">
                    <dl class="divide-y divide-gray-200">

                        {{-- Name Class --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">Name Sponsor</dt>
                            <dd class="col-span-2">
                                <input type="text" name="title" id="title"
                                    class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('title') border-red-500 @enderror"
                                    value="{{ old('title', $sponsor->title) }}" required autofocus>
                                @error('title')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>

                        {{-- Slug --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">Slug</dt>
                            <dd class="col-span-2">
                                <input type="text" name="slug" id="slug"
                                    class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('slug') border-red-500 @enderror"
                                    value="{{ old('slug', $sponsor->slug) }}" required>
                                @error('slug')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>

                        {{-- Image --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">Change Image</dt>
                            <dd class="col-span-2">
                                <input type="hidden" name="oldImage" value="{{ $sponsor->image }}">
                                @if($sponsor->image)
                                <img src="{{ asset('storage/' . $sponsor->image) }}" alt="Sponsor Image"
                                    class="img-preview mb-3 rounded-lg border border-gray-300 max-h-56 block">
                                @else
                                <img class="img-preview mb-3 rounded-lg border border-gray-300 max-h-56 hidden" alt="Image Preview">
                                @endif
                                <input type="file" id="image" name="image" accept="image/*"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none @error('image') border-red-500 @enderror"
                                    onchange="previewImage()">
                                @error('image')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>

                        {{-- Body --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-start">
                            <dt class="text-sm font-medium text-gray-600 pt-2">Description</dt>
                            <dd class="col-span-2">
                                @error('body')
                                <div class="text-red-600 text-sm mb-2">{{ $message }}</div>
                                @enderror
                                <input id="body" type="hidden" name="body" value="{{ old('body', $sponsor->body) }}">
                                <trix-editor input="body" class="trix-content rounded-md border border-gray-300 shadow-sm"></trix-editor>
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>

            <button type="submit"
                class="block w-full bg-orange-500 mt-6 py-3 rounded-2xl text-white font-semibold hover:bg-orange-600 transition duration-300">
                Update Sponsor
            </button>

            <a href="/dashboard/sponsors"
                class="block w-full mt-4 py-3 rounded-2xl text-center bg-blue-500 text-white font-semibold hover:bg-blue-700 transition duration-300">
                Back
            </a>
        </form>
    </div>
</div>

<script>
    const title = document.querySelector('#title');
    const slug = document.querySelector('#slug');

    title.addEventListener('change', function () {
        fetch('/dashboard/sponsors/checkSlug?title=' + encodeURIComponent(title.value))
            .then(response => response.json())
            .then(data => slug.value = data.slug)
    });

    document.addEventListener('trix-file-accept', function (e) {
        e.preventDefault();
    });

    function previewImage() {
        const image = document.querySelector('#image');
        const imgPreview = document.querySelector('.img-preview');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function (oFREvent) {
            imgPreview.src = oFREvent.target.result;
        };
    }
</script>
@endsection
