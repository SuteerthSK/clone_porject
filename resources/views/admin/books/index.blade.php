@extends('layouts.admin')

@section('header-title', 'Books')

@section('header-actions')
    <a href="{{ route('admin.books.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 transition-colors">
        Add Book
    </a>
@endsection

@section('content')
    {{-- Success Alert --}}
    @if (session('success'))
        <div class="bg-green-700/20 border border-green-600 text-green-400 px-4 py-3 rounded-md mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Books Table --}}
    <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-900 border-b border-gray-700">
            <tr>
                <th class="p-4 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">Cover</th>
                <th class="p-4 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">Title</th>
                <th class="p-4 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">Author</th>
                <th class="p-4 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse ($books as $book)
                    <tr class="hover:bg-gray-700 transition-colors">
                        <td class="p-4">
                            <div class="w-12 h-16 bg-cover rounded-md border border-gray-600" style='background-image: url("{{ $book->image }}");'></div>
                        </td>
                        <td class="p-4 font-semibold text-gray-100">{{ $book->title }}</td>
                        <td class="p-4 text-gray-400">{{ $book->author->name ?? 'N/A' }}</td>
                        <td class="p-4 text-sm font-bold">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="text-blue-400 hover:text-blue-500 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.books.destroy', $book) }}" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-500 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">No books found. Add one to get started!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
