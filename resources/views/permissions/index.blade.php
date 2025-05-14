@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Manage Permissions</h1>
        <form id="permissions-form" action="{{ route('permissions.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4 flex space-x-4">
                <div class="w-1/2">
                    <label class="block text-sm font-medium">Select Role</label>
                    <select id="role-select" name="role_id" class="mt-1 block w-full border rounded p-2">
                        <option value="">Select a Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ $selectedRoleId == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium">Select Employee</label>
                    <select id="employee-select" name="employee_id" class="mt-1 block w-full border rounded p-2">
                        <option value="">Select an Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ $selectedEmployeeId == $employee->id ? 'selected' : '' }}>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="hidden" name="type" id="type" value="{{ $selectedType }}">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Module</th>
                        @foreach ($actions as $action)
                            <th class="px-4 py-2 border">{{ ucfirst($action) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $module)
                        <tr>
                            <td class="px-4 py-2 border">{{ ucfirst($module) }}</td>
                            @foreach ($actions as $action)
                                <td class="px-4 py-2 border text-center">
                                    <input type="checkbox" name="permissions[]" value="{{ $action }}-{{ $module }}"
                                        {{ in_array("$action-$module", $selectedPermissions) ? 'checked' : '' }}>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    <tr>
                        <td class="px-4 py-2 border">Manage Permissions</td>
                        <td class="px-4 py-2 border text-center">
                            <input type="checkbox" name="permissions[]" value="manage-permissions"
                                {{ in_array('manage-permissions', $selectedPermissions) ? 'checked' : '' }}>
                        </td>
                        <td class="px-4 py-2 border" colspan="3"></td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Update Permissions</button>
        </form>
    </div>

    <script>
        function updatePermissions(type, id) {
            if (!id) return;

            document.getElementById('type').value = type;
            fetch('{{ route('permissions.get') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type, id })
            })
            .then(response => response.json())
            .then(data => {
                // Reset all checkboxes
                document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Check boxes for permissions
                data.permissions.forEach(permission => {
                    const checkbox = document.querySelector(`input[value="${permission}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            })
            .catch(error => console.error('Error fetching permissions:', error));
        }

        document.getElementById('role-select').addEventListener('change', function () {
            document.getElementById('employee-select').value = '';
            updatePermissions('role', this.value);
        });

        document.getElementById('employee-select').addEventListener('change', function () {
            document.getElementById('role-select').value = '';
            updatePermissions('employee', this.value);
        });
    </script>
@endsection