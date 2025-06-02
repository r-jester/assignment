<form action="{{ isset($employee) ? route('employees.update', $employee) : route('employees.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($employee))
        @method('PUT')
    @endif

    <!-- Tenant -->
    {{-- <div class="mb-3">
        <label for="tenant_id" class="form-label">Tenant</label>
        <select name="tenant_id" id="tenant_id" class="form-control">
            @foreach ($tenants as $tenant)
                <option value="{{ $tenant->id }}" {{ old('tenant_id', $employee->tenant_id ?? '') == $tenant->id ? 'selected' : '' }}>
                    {{ $tenant->name }}
                </option>
            @endforeach
        </select>
        @error('tenant_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div> --}}

    <!-- Business -->
    {{-- <div class="mb-3">
        <label for="business_id" class="form-label">Business</label>
        <select name="business_id" id="business_id" class="form-control">
            @foreach ($businesses as $business)
                <option value="{{ $business->id }}" {{ old('business_id', $employee->business_id ?? '') == $business->id ? 'selected' : '' }}>
                    {{ $business->name }}
                </option>
            @endforeach
        </select>
        @error('business_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div> --}}

    <!-- Department -->
    <div class="mb-3">
        <label for="department_id" class="form-label">Department</label>
        <select name="department_id" id="department_id" class="form-control">
            <option value="">Select Department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id ?? '') == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
        @error('department_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Position -->
    <div class="mb-3">
        <label for="position_id" class="form-label">Position</label>
        <select name="position_id" id="position_id" class="form-control">
            <option value="">Select Position</option>
            @foreach ($positions as $position)
                <option value="{{ $position->id }}" {{ old('position_id', $employee->position_id ?? '') == $position->id ? 'selected' : '' }}>
                    {{ $position->name }}
                </option>
            @endforeach
        </select>
        @error('position_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Role -->
    <div class="mb-3">
        <label for="role_id" class="form-label">Role</label>
        <select name="role_id" id="role_id" class="form-control">
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id', $employee->role_id ?? '') == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Spatie Permission Role -->
    <div class="mb-3">
        <label for="spatie_role" class="form-label">Permission Role</label>
        <select name="spatie_role" id="spatie_role" class="form-control">
            @foreach ($spatieRoles as $spatieRole)
                <option value="{{ $spatieRole->name }}"
                    {{ old('spatie_role', isset($employee) && $employee->roles->first() ? $employee->roles->first()->name : '') == $spatieRole->name ? 'selected' : '' }}>
                    {{ ucfirst($spatieRole->name) }}
                </option>
            @endforeach
        </select>
        @error('spatie_role')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Username -->
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $employee->username ?? '') }}">
        @error('username')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">Password {{ isset($employee) ? '(leave blank to keep unchanged)' : '' }}</label>
        <input type="password" name="password" id="password" class="form-control">
        @error('password')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
    </div>

    <!-- First Name -->
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', $employee->first_name ?? '') }}">
        @error('first_name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Last Name -->
    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', $employee->last_name ?? '') }}">
        @error('last_name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $employee->email ?? '') }}">
        @error('email')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Phone -->
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $employee->phone ?? '') }}">
        @error('phone')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Hire Date -->
    <div class="mb-3">
        <label for="hire_date" class="form-label">Hire Date</label>
        <input type="date" name="hire_date" id="hire_date" class="form-control" value="{{ old('hire_date', $employee->hire_date ?? '') }}">
        @error('hire_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Salary -->
    <div class="mb-3">
        <label for="salary" class="form-label">Salary</label>
        <input type="number" name="salary" id="salary" class="form-control" value="{{ old('salary', $employee->salary ?? '') }}" step="0.01">
        @error('salary')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Status -->
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-control">
            <option value="active" {{ old('status', $employee->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $employee->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="terminated" {{ old('status', $employee->status ?? '') == 'terminated' ? 'selected' : '' }}>Terminated</option>
        </select>
        @error('status')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Profile Image -->
    <div class="mb-3">
        <label for="image" class="form-label">Profile Image</label>
        <input type="file" name="image" id="image" class="form-control">
        @if(isset($employee) && $employee->image)
            <img src="{{ Storage::url($employee->image) }}" alt="Profile Image" class="img-thumbnail mt-2" style="max-width: 100px;">
        @endif
        @error('image')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Buttons -->
    <button type="submit" class="btn btn-primary">{{ isset($employee) ? 'Update' : 'Create' }}</button>
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
</form>
