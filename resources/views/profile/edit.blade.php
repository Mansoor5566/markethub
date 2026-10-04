<x-app-layout>
<x-slot name="title">Edit Profile</x-slot>

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">Edit Profile</h1>

    {{-- Profile Info --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h2 class="font-bold text-gray-900 mb-6">Personal Information</h2>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PATCH')

            {{-- Avatar --}}
            <div class="flex items-center gap-6 mb-6">
                <div class="relative">
                    @if($user->avatar)
                        <img id="avatar-preview"
                             src="{{ Storage::url($user->avatar) }}"
                             class="w-20 h-20 rounded-full object-cover border-2 border-brand-200">
                    @else
                        <div id="avatar-initials"
                             class="w-20 h-20 rounded-full bg-brand-500 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <img id="avatar-preview" class="w-20 h-20 rounded-full object-cover border-2 border-brand-200 hidden">
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Profile Photo</label>
                    <input type="file" name="avatar" accept="image/*"
                           onchange="previewAvatar(this, 'avatar-preview')"
                           class="text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg
                                  file:border-0 file:text-xs file:font-semibold file:bg-brand-50
                                  file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                    @error('avatar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none
                                  {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-100' }}">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-400 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">Email cannot be changed.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="+92 300 0000000"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none
                                  border-gray-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bio</label>
                    <textarea name="bio" rows="3" placeholder="Tell others about yourself..."
                        class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none resize-none
                               border-gray-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('bio', $user->bio) }}</textarea>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="bg-brand-600 text-white px-8 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="font-bold text-gray-900 mb-6">Change Password</h2>

        <form method="POST" action="{{ route('profile.password') }}">
            @csrf @method('PATCH')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Current Password</label>
                    <input type="password" name="current_password"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none
                                  {{ $errors->has('current_password') ? 'border-red-400' : 'border-gray-300 focus:border-brand-500' }}">
                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none
                                  {{ $errors->has('password') ? 'border-red-400' : 'border-gray-300 focus:border-brand-500' }}">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none
                                  border-gray-300 focus:border-brand-500">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="bg-gray-900 text-white px-8 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-700 transition-colors">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.querySelector('input[name="avatar"]')?.addEventListener('change', function() {
    const preview = document.getElementById('avatar-preview');
    const initials = document.getElementById('avatar-initials');
    if (preview) preview.classList.remove('hidden');
    if (initials) initials.classList.add('hidden');
});
</script>
@endpush
</x-app-layout>