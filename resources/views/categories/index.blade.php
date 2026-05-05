@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">Categories</h2>

    <a href="{{ route('categories.create') }}" 
       class="bg-blue-500 text-white px-4 py-2 rounded">
        + Add Category
    </a>

    <table class="w-full mt-4 border">
        <tr class="bg-gray-200">
            <th class="p-2">Name</th>
            <th class="p-2">Action</th>
        </tr>

        @foreach($categories as $category)
        <tr>
            <td class="p-2">{{ $category->name }}</td>
            <td class="p-2">
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="bg-red-500 text-white px-3 py-1 rounded">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach

    </table>

</div>

@endsection