@extends('dashboard.layouts.main')


@section('container')

<div class="p-10 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-20">

        <div class="realtive d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="text-3xl font-bold text-black">Student List</h1>
        </div>

        @if(session()->has('success'))
        <div class="flex items-center p-4 mt-8 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-900 dark:text-green-400 dark:border-green-800 mb-4" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="mb-4">
            <label for="sort" class="text-sm font-medium text-gray-900">Sort by:</label>
            <div class="flex justify-left">
            <select id="sort" name="sort" class="flex justify-center w-1/4 mt-1 mr-2 rounded-md shadow-lg b-md focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>A-Z</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z-A</option>
            </select>
            <button class="flex justify-center bg-orange-500 hover:bg-orange-600 text-white rounded-md h-2/4 px-4 py-2" onclick="applySort()">Apply</button>

            <div id="app" class="">
                <div class="flex justify-center">
                    <input type="text" id="search" name="search" v-model="search" placeholder="Search" class="flex w-3/4 rounded-md shadow-lg ml-4 mr-2 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                    <button class="bg-orange-500 hover:bg-orange-600 text-white rounded-md px-4 py-2" @click="searchNews">Search</button>
                </div>
            </div>

            <div class="flex justify-right">
                  <a href="{{ route('pdfStudent') }}">
                    <button class="bg-orange-500 hover:bg-orange-600 text-white rounded-md px-4 py-2 ml-4" @click="searchNews">Download PDF</button>
                    </a>
                </div>
        </div>

            <script>
            function applySort() {
                var sortValue = document.getElementById('sort').value;
                window.location.href = '/dashboard/student?sort=' + sortValue;
            }
        </script>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            search: ''
        },
        methods: {
            searchNews: function() {
                window.location.href = '/dashboard/student?search=' + this.search;
            }
        }
    });
</script>

            <div class="mt-4 relative table-responsive col-lg-8">
                <table class="w-full text-lg text-left rtl:text-right text-gray-900 dark:text-gray-900">
                    <thead class="text-xs text-gray-100 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user as $u)
                        <tr class="bg-white border-b dark:bg-gray-100 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">
                                {{ $loop->iteration }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">
                                {{ $u->name }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">
                                {{ $u->email }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">
                                @if($u->is_admin === 1)
                                <span class="text-orange-500 ml-1">Admin</span>
                                @else
                                <span class="text-blue-500 ml-1">Student</span>
                                @endif
                            </th>

                            <td class="flex ml-8 col-lg-8 mt-5">
                                <form action="{{ route('deleteUser', $u->id) }}" method="post" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" onclick="return confirm('Are you sure?')">
                                        <span data-feather="x-circle" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-1 rounded"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

    @endsection