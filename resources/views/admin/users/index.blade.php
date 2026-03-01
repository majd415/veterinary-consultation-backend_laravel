@extends('admin.layout')

@section('title', __('admin.users'))

@section('content')
    <!-- Action Bar -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
        <button onclick="openModal('add')" class="btn btn-primary">
            + {{ __('admin.add_new') }}
        </button>
    </div>

    @if(session('success'))
        <div class="card" style="margin-bottom: 1rem; border-color: rgba(34, 197, 94, 0.3); color: #4ade80;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Users Table -->
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.name') }}</th>
                    <th>{{ __('admin.role') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>#{{ $user->id }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #334be9; display: flex; align-items: center; justify-content: center; font-weight: bold; overflow: hidden;">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <div style="font-weight: 500;">{{ $user->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $user->role == 'vet' ? 'badge-warning' : 'badge-success' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->is_admin)
                            <span class="badge" style="background: rgba(99, 102, 241, 0.2); color: #818cf8;">
                                {{ $user->admin_role ? ucfirst(str_replace('_', ' ', $user->admin_role)) : 'Admin' }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <span style="color: {{ $user->email_verified_at ? '#4ade80' : '#f87171' }}">
                            {{ $user->email_verified_at ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editUser({{ json_encode($user) }})" class="btn" style="background: rgba(255, 255, 255, 0.1); color: var(--text);">✏️</button>
                            
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div style="margin-top: 2rem;">
            {{ $users->links() }} 
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="userModal" class="overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">{{ __('admin.add_new') }}</h2>
                <button class="close-modal" onclick="closeModal()">✕</button>
            </div>
            
            <form id="userForm" method="POST">
                @csrf
                <div id="methodField"></div>

                <div style="margin-bottom: 1.25rem;">
                    <label>Full Name</label>
                    <input type="text" name="name" id="name" required placeholder="John Doe">
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label>Email Address</label>
                    <input type="email" name="email" id="email" required placeholder="john@example.com">
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••">
                    <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">Leave blank to stay unchanged when editing.</small>
                </div>

                <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                    <div style="flex: 1;">
                        <label>App Role</label>
                        <select name="role" id="role">
                            <option value="user">Normal User</option>
                            <option value="vet">Veterinarian</option>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <label>Admin Access</label>
                        <select name="is_admin" id="is_admin" onchange="toggleAdminRole()">
                            <option value="0">No Access</option>
                            <option value="1">Admin Access</option>
                        </select>
                    </div>
                </div>

                <div id="adminRoleSection" style="margin-bottom: 2rem; display: none;">
                    <label>Admin Dashboard Role</label>
                    <select name="admin_role" id="admin_role">
                        <option value="super_admin">Super Admin (All Access)</option>
                        <option value="accountant">Accountant</option>
                        <option value="data_entry">Data Entry</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid var(--border); padding-top: 1.25rem;">
                    <button type="button" onclick="closeModal()" class="btn" style="background: rgba(255,255,255,0.05); color: var(--text-muted);">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary" style="padding-left: 2rem; padding-right: 2rem;">✨ {{ __('admin.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAdminRole() {
            const isAdmin = document.getElementById('is_admin').value == '1';
            document.getElementById('adminRoleSection').style.display = isAdmin ? 'block' : 'none';
        }

        function openModal(mode) {
            document.getElementById('userModal').classList.add('show');
            if (mode === 'add') {
                document.getElementById('modalTitle').innerText = "{{ __('admin.add_new') }}";
                document.getElementById('userForm').action = "{{ route('admin.users.store') }}";
                document.getElementById('methodField').innerHTML = '';
                document.getElementById('name').value = '';
                document.getElementById('email').value = '';
                document.getElementById('role').value = 'user';
                document.getElementById('is_admin').value = '0';
                document.getElementById('admin_role').value = 'data_entry';
                document.getElementById('password').required = true;
                toggleAdminRole();
            }
        }

        function editUser(user) {
            document.getElementById('userModal').classList.add('show');
            document.getElementById('modalTitle').innerText = "{{ __('admin.edit') }}";
            document.getElementById('userForm').action = "users/" + user.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('role').value = user.role;
            document.getElementById('is_admin').value = user.is_admin ? '1' : '0';
            document.getElementById('admin_role').value = user.admin_role || 'data_entry';
            document.getElementById('password').required = false;
            toggleAdminRole();
        }

        function closeModal() {
            document.getElementById('userModal').classList.remove('show');
        }
    </script>

@endsection
