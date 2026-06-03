@extends('layouts.app')

@section('title', 'Admin Login — Community Meter')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">Admin Access</h1>

        @if($errors->any())
        <div class="bg-red-50 border border-red-300 rounded-lg p-4 mb-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" autofocus required
                    class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit"
                class="w-full py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-lg transition text-sm">
                Sign In
            </button>
        </form>
    </div>
</div>
@endsection
