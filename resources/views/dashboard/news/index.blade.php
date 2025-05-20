@extends('dashboard.layouts.main')

@section('container')

<div class="sm:ml-64 p-10">
    <div class="mt-20">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Post Product</h1>
            <p class="text-gray-500 mt-1">Manage all news data</p>
        </div>

        <!-- Success alert -->
        @if(session()->has('success'))
        <div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-300 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <!-- Card -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">

            <!-- Actions: Create, Search, Sort, Download -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <!-- Left -->
                <div class="flex flex-wrap items-center gap-3">
                    <a href="/dashboard/news/create" class="bg-orange-500 hover:bg-orange-600 text-white font-medium px-4 py-2 rounded-md">
                        + Create News
                    </a>

                    <select id="sort" name="sort" class="rounded-md border-gray-300 text-sm" onchange="applySort()">
                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Sort A-Z</option>
                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Sort Z-A</option>
                    </select>
                </div>

                <!-- Right -->
                <div class="flex flex-wrap items-center gap-3" id="app">
                    <input type="text" v-model="search" placeholder="Search news..." class="rounded-md border border-gray-300 text-sm px-3 py-2 w-64 focus:ring-orange-300 focus:border-orange-500">
                    <button @click="searchNews" class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-2 rounded-md">
                        Search
                    </button>
                    <a href="{{ route('pdfReport') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-2 rounded-md">
                        Download PDF
                    </a>
                </div>

            </div>

            <!-- News Table -->
            @if(count($news) > 0)
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-left text-gray-800">
                    <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Title</th>
                            <th class="px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($news as $n)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $n->title }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="/dashboard/news/{{ $n->slug }}" class="text-green-600 hover:text-green-800" title="View">
                                    <i data-feather="eye"></i>
                                </a>
                                <a href="/dashboard/news/{{ $n->slug }}/edit" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                    <i data-feather="edit"></i>
                                </a>
                                <form action="/dashboard/news/{{ $n->slug }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $news->links() }}
            </div>

            @else
            <p class="text-gray-500 text-center py-6">No news found.</p>
            @endif
        </div>

    </div>
</div>

<!-- Vue.js & Feather Icons -->
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script>
    function applySort() {
        const sort = document.getElementById('sort').value;
        window.location.href = `/dashboard/news?sort=${sort}`;
    }

    new Vue({
        el: '#app',
        data: {
            search: '{{ request("search") }}'
        },
        methods: {
            searchNews() {
                window.location.href = `/dashboard/news?search=${this.search}`;
            }
        }
    });

    feather.replace();
</script>

@endsection
