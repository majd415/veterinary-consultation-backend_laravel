@extends('admin.layout')

@section('title', 'Top Rated Items')

@section('content')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
        <button onclick="openModal('add')" class="btn btn-primary">+ Add Item</button>
    </div>

    @if(session('success'))
        <div class="card" style="margin-bottom: 1rem; border-color: #22c55e; color: #4ade80;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>
                        <img src="{{ asset($item->image) }}" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border);">
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ is_array($item->name) ? ($item->name['en'] ?? '') : $item->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); direction: rtl;">{{ is_array($item->name) ? ($item->name['ar'] ?? '') : '' }}</div>
                    </td>
                    <td>⭐ {{ $item->rating }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editItem({{ json_encode($item) }})" class="btn" style="background: rgba(255, 255, 255, 0.1);">✏️</button>
                            <form action="{{ route('admin.top_rated.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top: 1rem;">{{ $items->links() }}</div>
    </div>

    <!-- Modal -->
    <div id="itemModal" class="overlay">
        <div class="card" style="margin: 10vh auto; max-width: 400px;">
            <h2 id="modalTitle">Add Item</h2>
            <form id="itemForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>
                
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <div style="flex: 1;">
                        <label>Name (EN)</label>
                        <input type="text" name="name" id="name" required style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 0.5rem; color: white;">
                    </div>
                    <div style="flex: 1;">
                        <label style="direction: rtl; display: block;">الاسم (AR)</label>
                        <input type="text" name="name_ar" id="name_ar" required style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 0.5rem; color: white; direction: rtl;">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label>Rating</label>
                    <input type="number" step="0.1" name="rating" id="rating" style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 0.5rem; color: white;">
                </div>

                <div style="margin-bottom: 2rem;">
                    <label>Image</label>
                    <div style="position: relative;">
                        <input type="file" name="image" id="imageInput" style="display: none;" onchange="previewImage(this)">
                        <div onclick="document.getElementById('imageInput').click()" style="cursor: pointer; border: 2px dashed var(--border); border-radius: 1rem; padding: 2rem; text-align: center;">
                            <span id="imageIcon" style="font-size: 2rem;">🖼️</span>
                            <div id="fileName" style="margin-top: 0.5rem; color: var(--text-muted);">Click to upload</div>
                            <img id="imagePreview" style="display: none; width: 100%; height: 150px; object-fit: cover; border-radius: 0.5rem; margin-top: 1rem;">
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                    <button type="button" onclick="closeModal()" class="btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(mode) {
            document.getElementById('itemModal').classList.add('show');
            if (mode === 'add') {
                document.getElementById('modalTitle').innerText = 'Add Item';
                document.getElementById('itemForm').action = "{{ route('admin.top_rated.store') }}";
                document.getElementById('methodField').innerHTML = '';
                document.getElementById('name').value = '';
                document.getElementById('name_ar').value = '';
                document.getElementById('rating').value = '5';
                document.getElementById('imageInput').required = true;
                resetImagePreview();
            }
        }

        function editItem(item) {
            document.getElementById('itemModal').classList.add('show');
            document.getElementById('modalTitle').innerText = 'Edit Item';
            document.getElementById('itemForm').action = "top_rated/" + item.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('name').value = typeof item.name === 'object' ? (item.name.en || '') : item.name;
            document.getElementById('name_ar').value = typeof item.name === 'object' ? (item.name.ar || '') : '';
            document.getElementById('rating').value = item.rating;
            document.getElementById('imageInput').required = false;
            
            if (item.image) {
                document.getElementById('imagePreview').src = "{{ asset('') }}" + item.image;
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('imageIcon').style.display = 'none';
            }
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('imageIcon').style.display = 'none';
                    document.getElementById('fileName').innerText = input.files[0].name;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function resetImagePreview() {
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('imageIcon').style.display = 'block';
            document.getElementById('fileName').innerText = 'Click to upload';
        }

        function closeModal() {
            document.getElementById('itemModal').classList.remove('show');
        }
    </script>
    <style>
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); z-index: 100; }
        .overlay.show { display: block; }
    </style>
@endsection
