@extends('layouts.app')
  @section('title', 'Edit Role')
  @section('content')
      <h1>Edit Role</h1>
      <form action="{{ route('roles.update', $role) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name) }}">
              @error('name')
                  <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>
          <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea name="description" id="description" class="form-control">{{ old('description', $role->description) }}</textarea>
              @error('description')
                  <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
      </form>
  @endsection