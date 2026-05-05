@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow max-w-md">

    <h2 class="text-xl font-bold mb-4">Add Category</h2>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Category Name</label>
            <input type="text" name="name" 
                   class="w-full border p-2 rounded" 
                   required>
        </div>

        <button class="bg-green-500 text-white px-4 py-2 rounded">
            Save Category
        </button>

    </form>

</div>

@endsection