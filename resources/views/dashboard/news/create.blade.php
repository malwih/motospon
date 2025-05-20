@extends('dashboard.layouts.main')

@section('container')

<div class="sm:ml-64 p-10">
  <div class="mt-20">

    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Create News</h1>
      <p class="text-gray-500 mt-1">Add new news post</p>
    </div>

    <!-- Card Form -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 max-w-2xl">

      <form method="POST" action="/dashboard/news" enctype="multipart/form-data">
        @csrf

        <!-- Title -->
        <div class="mb-5">
          <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
          <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm @error('title') border-red-500 @enderror">
          @error('title')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Slug -->
        <div class="mb-5">
          <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
          <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm @error('slug') border-red-500 @enderror">
          @error('slug')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Image Upload -->
        <div class="mb-5">
          <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
          <img class="w-48 h-auto rounded-md img-preview mb-3 hidden">
          <input type="file" id="image" name="image"
            class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:border-orange-500 focus:ring-orange-500 @error('image') border-red-500 @enderror"
            onchange="previewImage()">
          @error('image')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Body -->
        <div class="mb-6">
          <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Body</label>
          @error('body')
          <p class="text-red-500 text-sm mb-1">{{ $message }}</p>
          @enderror
          <input id="body" type="hidden" name="body" value="{{ old('body') }}">
          <trix-editor input="body" class="trix-content bg-white border border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500"></trix-editor>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button type="submit"
            class="bg-orange-500 hover:bg-orange-600 text-white font-medium text-sm px-5 py-2.5 rounded-lg">
            Create News
          </button>
        </div>
      </form>

    </div>

  </div>
</div>

<!-- Scripts -->
<script>
  const title = document.querySelector('#title');
  const slug = document.querySelector('#slug');

  title.addEventListener('change', function () {
    fetch('/dashboard/sponsors/checkSlug?title=' + title.value)
      .then(response => response.json())
      .then(data => slug.value = data.slug);
  });

  document.addEventListener('trix-file-accept', function (e) {
    e.preventDefault();
  });

  function previewImage() {
    const image = document.querySelector('#image');
    const imgPreview = document.querySelector('.img-preview');
    const reader = new FileReader();

    reader.onload = function (e) {
      imgPreview.src = e.target.result;
      imgPreview.classList.remove('hidden');
    }

    if (image.files[0]) {
      reader.readAsDataURL(image.files[0]);
    }
  }
</script>

@endsection
