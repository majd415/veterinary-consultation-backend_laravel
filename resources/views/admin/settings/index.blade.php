@extends('admin.layout')

@section('title', 'System Settings')

@section('content')
    <div class="grid-4">
        <!-- Logo Update -->
        <div class="card">
            <h3>App Logo</h3>
            <div style="text-align: center; padding: 2rem; border: 2px dashed var(--border); border-radius: 1rem; margin-bottom: 1.5rem;">
                @if($logo)
                    <img src="{{ asset($logo) }}" style="max-width: 100%; height: 100px; object-fit: contain;">
                @else
                    <div style="font-size: 3rem;">🐕</div>
                    <div style="color: var(--text-muted);">No Logo Uploaded</div>
                @endif
            </div>
            <form action="{{ route('admin.settings.logo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="logo" id="logoInput" style="display: none;" onchange="this.form.submit()">
                <button type="button" onclick="document.getElementById('logoInput').click()" class="btn btn-primary" style="width: 100%;">
                    Change Logo
                </button>
            </form>
        </div>

        <!-- Other Info -->
        <div class="card" style="grid-column: span 2;">
            <h3>App Information</h3>
            <table>
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($settings as $s)
                    @if($s->key != 'logo')
                    <tr>
                        <td style="font-weight: bold;">{{ strtoupper($s->key) }}</td>
                        <td>{{ $s->value }}</td>
                        <td>
                             <form action="{{ route('admin.settings.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Remove?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
