@extends('layouts.app')
@section('title', 'Manage Resources – HealNest')
@section('page-title', 'Manage Resources')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6 space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, category, or description..."
                   class="border border-tan/40 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
            <select name="category" class="border border-tan/40 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-forest text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                Filter
            </button>
        </form>

        <a href="{{ route('admin.resources.create') }}"
           class="bg-tan text-forest px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-lightgreen transition-colors">
            + New Resource
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-tan/20 text-left text-gray-500 text-xs uppercase">
                    <th class="pb-3 pr-4">Resource</th>
                    <th class="pb-3 pr-4">Category</th>
                    <th class="pb-3 pr-4">URL</th>
                    <th class="pb-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-tan/10">
                @forelse($resources as $resource)
                <tr class="hover:bg-cream/50">
                    <td class="py-4 pr-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">{{ $resource->icon }}</span>
                            <div>
                                <p class="font-semibold text-forest">{{ $resource->title }}</p>
                                <p class="text-xs text-gray-500 mt-1 max-w-xl">{{ $resource->desc }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 pr-4 text-gray-600">{{ $resource->category }}</td>
                    <td class="py-4 pr-4 text-gray-500 text-xs break-all">
                        {{ $resource->external_url ?? '—' }}
                    </td>
                    <td class="py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.resources.edit', $resource->_id) }}" class="text-xs text-midgreen hover:underline font-semibold">Edit</a>
                            <form method="POST" action="{{ route('admin.resources.delete', $resource->_id) }}" onsubmit="return confirm('Delete this resource?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="4" class="py-10 text-center text-gray-400">No resources found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $resources->withQueryString()->links() }}
    </div>
</div>
@endsection
