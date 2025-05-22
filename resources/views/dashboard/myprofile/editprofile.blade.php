@extends('dashboard.layouts.main')
@php use Illuminate\Support\Str; @endphp
@section('container')

<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="p-10 sm:ml-64">
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-3xl mx-auto mt-20">
        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
        </div>

        @if(session()->has('success'))
        <div class="flex items-center p-4 mt-4 mb-6 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <form id="editForm" action="{{ route('editprofile.update') }}" method="POST" enctype="multipart/form-data" class="mt-6">
            @csrf
            @method('PUT')

            {{-- Profile Picture --}}
            <div class="flex justify-center mb-8">
                <div class="relative w-28 h-28 group cursor-pointer">
                    @php $avatar = $user->avatar; @endphp

                    @if($avatar && Str::startsWith($avatar, 'http'))
                        <img src="{{ $avatar }}" class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" alt="Google Photo">
                    @elseif($avatar)
                        <img src="{{ asset('storage/' . $avatar) }}" class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" alt="Profile Photo">
                    @else
                        <img src="{{ asset('img/user.png') }}" class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" alt="Default Photo">
                    @endif

                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center text-white font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                        Ubah
                    </div>
                    <input type="file" name="avatar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 rounded-full cursor-pointer">
                </div>
            </div>
            <input type="hidden" name="cropped_avatar" id="croppedAvatar">
            @error('avatar')
                <p class="text-sm text-red-600 text-center mt-1">{{ $message }}</p>
            @enderror

            {{-- Form Fields --}}
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-6 py-6">
                    <dl class="divide-y divide-gray-200">

                        @foreach (['name' => 'Full Name', 'username' => 'Username', 'email' => 'Email'] as $field => $label)
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">{{ $label }}</dt>
                            <dd class="col-span-2">
                                <input type="{{ $field === 'email' ? 'email' : 'text' }}"
                                       name="{{ $field }}"
                                       id="{{ $field }}"
                                       value="{{ old($field, $user->$field) }}"
                                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error($field) border-red-500 @enderror"
                                       required>
                                @error($field)
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>
                        @endforeach

                        {{-- New Password --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">New Password</dt>
                            <dd class="col-span-2">
                                <input type="password" name="password" id="password"
                                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('password') border-red-500 @enderror"
                                       placeholder="(Optional)">
                                @error('password')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">Confirm Password</dt>
                            <dd class="col-span-2">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500"
                                       placeholder="(Optional)">
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>

            {{-- Buttons --}}
            <button type="submit"
                class="block w-full bg-orange-500 mt-6 py-3 rounded-2xl text-white font-semibold hover:bg-orange-600 transition duration-300">
                Update Profile
            </button>

            <a href="/dashboard/myprofile"
                class="block w-full mt-4 py-3 rounded-2xl text-center bg-blue-500 text-white font-semibold hover:bg-blue-700 transition duration-300">
                Back
            </a>
        </form>
    </div>
</div>

{{-- Cropper Modal --}}
<div id="cropModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md max-h-screen overflow-auto">
        <div class="text-lg font-bold mb-4 text-center">Crop Your Avatar</div>
        <div class="flex justify-center">
            <img id="imageToCrop" class="max-w-full max-h-96 object-contain rounded-md shadow" />
        </div>
        <div class="mt-4 flex justify-end space-x-2">
            <button id="cancelCrop" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
            <button id="confirmCrop" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Crop</button>
        </div>
    </div>
</div>


{{-- JS for SweetAlert & Cropper --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('editForm');
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Update Profile?',
                text: "Pastikan semua data sudah benar sebelum diupdate.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                didOpen: () => {
                    Swal.getConfirmButton().style.background = '#16a34a';
                    Swal.getCancelButton().style.background = '#d33';
                    Swal.getConfirmButton().style.color = '#fff';
                    Swal.getCancelButton().style.color = '#fff';
                }
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

        // Cropper logic
        let cropper;
        const avatarInput = document.querySelector('input[name="avatar"]');
        const cropModal = document.getElementById('cropModal');
        const imageToCrop = document.getElementById('imageToCrop');
        const croppedAvatarInput = document.getElementById('croppedAvatar');
        const previewImage = document.querySelector('.group img');

        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                imageToCrop.src = event.target.result;
                cropModal.classList.remove('hidden');

                if (cropper) cropper.destroy();
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                });
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('cancelCrop').addEventListener('click', () => {
            cropModal.classList.add('hidden');
            avatarInput.value = '';
            cropper?.destroy();
        });

        document.getElementById('confirmCrop').addEventListener('click', () => {
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300,
            });
            canvas.toBlob(function (blob) {
                const reader = new FileReader();
                reader.onloadend = function () {
                    const base64data = reader.result;
                    croppedAvatarInput.value = base64data;
                    previewImage.src = base64data;
                    cropModal.classList.add('hidden');
                    cropper.destroy();
                };
                reader.readAsDataURL(blob);
            });
        });
    });
</script>

@endsection
