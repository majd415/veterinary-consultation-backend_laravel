@extends('admin.layout')

@section('title', 'Chat Requests')

@section('content')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Ref ID</th>
                    <th>User</th>
                    <th>Vet</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr>
                    <td>#{{ $req->id }}</td>
                    <td>{{ $req->user->name ?? 'User #'.$req->customer_id }}</td>
                    <td>{{ $req->vet->name ?? 'Vet #'.$req->taken_by }}</td>
                    <td>
                         <form action="{{ route('admin.chats.update', $req->id) }}" method="POST">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" style="background: rgba(0,0,0,0.3); color: white; border: 1px solid var(--border); border-radius: 4px; padding: 2px;">
                                <option value="pending" {{ $req->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ $req->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ $req->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="completed" {{ $req->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                    </td>
                    <td>{{ $req->created_at->diffForHumans() }}</td>
                    <td>
                         <form action="{{ route('admin.chats.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Remove?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top: 1rem;">{{ $requests->links() }}</div>
    </div>
@endsection
