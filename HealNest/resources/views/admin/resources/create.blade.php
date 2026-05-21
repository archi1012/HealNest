@extends('layouts.app')
@section('title', 'Create Resource – HealNest')
@section('page-title', 'Create Resource')

@section('content')
<div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
    <form method="POST" action="{{ route('admin.resources.store') }}" class="space-y-5">
        @csrf

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border border-tan/30 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <input type="text" name="category" value="{{ old('category') }}"
                       class="w-full border border-tan/30 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                <input type="text" name="icon" value="{{ old('icon') }}" placeholder="📚"
                       class="w-full border border-tan/30 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">External URL</label>
                <input type="url" name="external_url" value="{{ old('external_url') }}" placeholder="https://..."
                       class="w-full border border-tan/30 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="desc" rows="5"
                      class="w-full border border-tan/30 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">{{ old('desc') }}</textarea>
        </div>

        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('admin.resources.index') }}" class="text-sm text-gray-500 hover:text-forest">Cancel</a>
            <button type="submit" class="bg-forest text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                Save Resource
            </button>
        </div>
    </form>
</div>
@endsection
