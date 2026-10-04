<x-admin-layout>
<x-slot name="title">Users</x-slot>

{{-- Filters --}}
<div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search name or email..."
               class="flex-1 min-w-[200px] px-4 py-2 text-sm border border-gray-300 rounded-xl outline-none focus:border-brand-500">
        <select name="role"
            class="px-4 py-2 text-sm border border-gray-300 rounded-xl outline-none focus:border-brand-500">
            <option value="">All Roles</option>
            <option value="admin"  {{ request('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
            <option value="seller" {{ request('role') === 'seller' ? 'selected' : '' }}>Seller</option>
            <option value="buyer"  {{ request('role') === 'buyer'  ? 'selected' : '' }}>Buyer</option>
        </select>
        <select name="status"
            class="px-4 py-2 text-sm border border-gray-300 rounded-xl outline-none focus:border-brand-500">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
        </select>
        <button type="submit"
            class="bg-brand-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
            Filter
        </button>
        <a href="{{ route('admin.users.index') }}"
           class="bg-gray-100 text-gray-700 px-5 py-2 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
            Clear
        </a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">User</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Role</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Joined</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-brand-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="font-semibold text-gray-900 hover:text-brand-600">
                                    {{ $user->name }}
                                </a>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.users.role', $user) }}">
                            @csrf @method('PATCH')
                            <select name="role" onchange="this.form.submit()"
                                class="text-xs border border-gray-300 rounded-lg px-2 py-1 outline-none focus:border-brand-500">
                                @foreach(['buyer', 'seller', 'admin'] as $role)
                                <option value="{{ $role }}"
                                    {{ $user->getRoleNames()->first() === $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-green-100 text-green-700">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-red-100 text-red-700">
                                Banned
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="px-3 py-1.5 text-xs font-semibold text-brand-600 border border-brand-300 rounded-lg hover:bg-brand-50 transition-colors">
                                View
                            </a>
                            @if($user->is_active)
                                <form method="POST" action="{{ route('admin.users.ban', $user) }}"
                                      onsubmit="return confirm('Ban {{ $user->name }}?')">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-semibold text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                                        Ban
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-semibold text-green-600 border border-green-300 rounded-lg hover:bg-green-50 transition-colors">
                                        Unban
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
</div>

</x-admin-layout>