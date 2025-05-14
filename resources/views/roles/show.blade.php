@extends('layouts.app')
  @section('title', 'Role Details')
  @section('content')
      <h1>Role: {{ $role->name }}</h1>
      <p><strong>Description:</strong> {{ $role->description ?? '-' }}</p>
      <a href="{{ route('roles.index') }}" class="btn btn-primary">Back to Roles</a>
      @can('edit-roles')
          <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">Edit</a>
      @endcan
      @can('delete-roles')
          <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
          </form>
      @endcan
  @endsection