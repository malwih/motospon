<aside id="logo-sidebar" 
       class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700" 
       aria-label="Sidebar">

    <div class="flex flex-col h-full bg-gray-100 p-6">
        <div class="bg-white rounded-lg p-8 pt-20 shadow-lg flex flex-col items-center">
            
        <div class="flex justify-center items-center mb-6">
    @php $avatar = auth()->user()->avatar; @endphp

    @if($avatar && Str::startsWith($avatar, 'http'))
        {{-- Avatar dari Google atau URL eksternal --}}
        <img class="w-20 h-20 rounded-full object-cover border-4 border-orange-400" src="{{ $avatar }}" alt="Google Profile Photo">
    @elseif($avatar)
        {{-- Avatar dari penyimpanan lokal --}}
        <img class="w-20 h-20 rounded-full object-cover border-4 border-orange-400" src="{{ asset('storage/' . $avatar) }}" alt="Profile Photo">
    @else
        {{-- Default avatar --}}
        <img class="w-20 h-20 rounded-full object-cover border-4 border-orange-400" src="{{ asset('default-avatar.png') }}" alt="Default Profile Photo">
    @endif
</div>

            
            <ul class="space-y-3 text-sm font-medium w-full">
                <span class="block mb-2 text-xs font-semibold uppercase text-gray-400">Community</span>
                <li>
                    <a href="/dashboard/community" 
                       class="flex items-center space-x-3 p-2 rounded-md font-medium
                       {{ Request::is('dashboard/community') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                        <svg class="h-5 w-5 {{ Request::is('dashboard/community') ? 'text-white' : 'text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/dashboard/sponsorship" 
                       class="flex items-center space-x-3 p-2 rounded-md font-medium
                       {{ Request::is('dashboard/sponsorship*') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                        <svg class="h-5 w-5 {{ Request::is('dashboard/sponsorship*') ? 'text-white' : 'text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Sponsorship</span>
                    </a>
                </li>
                <li>
                    <a href="/dashboard/myprofile" 
                       class="flex items-center space-x-3 p-2 rounded-md font-medium
                       {{ Request::is('dashboard/myprofile') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                        <svg class="h-5 w-5 {{ Request::is('dashboard/myprofile') ? 'text-white' : 'text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>My Profile</span>
                    </a>
                </li>
            </ul>

            @can('company')
            <div class="w-full">
                <span class="block mb-2 text-xs font-semibold uppercase text-gray-400">Company</span>
                <ul class="space-y-3 text-sm font-medium">
                    <li>
                    <a href="/dashboard/company" 
                       class="flex items-center space-x-3 p-2 rounded-md font-medium
                       {{ Request::is('dashboard/company') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                        <svg class="h-5 w-5 {{ Request::is('dashboard/company') ? 'text-white' : 'text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/dashboard/myprofile" 
                       class="flex items-center space-x-3 p-2 rounded-md font-medium
                       {{ Request::is('dashboard/myprofile') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                        <svg class="h-5 w-5 {{ Request::is('dashboard/myprofile') ? 'text-white' : 'text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>My Profile</span>
                    </a>
                </li>
                    <li>
                        <a href="/dashboard/news" 
                           class="flex items-center space-x-3 p-2 rounded-md font-medium
                           {{ Request::is('dashboard/news*') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                            <svg class="h-5 w-5 {{ Request::is('dashboard/news*') ? 'text-white' : 'text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h6v6H6zm7 8H6v2h12v-2h-4zm1-4h4v2h-4zm0-4h4v2h-4z" />
                            </svg>
                            <span>News</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard/sponsors" 
                           class="flex items-center space-x-3 p-2 rounded-md font-medium
                           {{ Request::is('dashboard/sponsors*') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                            <svg class="h-5 w-5 {{ Request::is('dashboard/sponsors*') ? 'text-white' : 'text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            <span>Sponsorship</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard/student" 
                           class="flex items-center space-x-3 p-2 rounded-md font-medium
                           {{ Request::is('dashboard/student*') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                            <svg class="h-5 w-5 {{ Request::is('dashboard/student*') ? 'text-white' : 'text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span>Student List</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endcan
        </div>
    </div>
</aside>
