@extends('admin.layout')

@section('title', 'Slider Offers')

@section('content')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
        <button onclick="openModal('add')" class="btn btn-primary">+ Add Slider</button>
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
                    <th>Banner</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sliders as $slide)
                <tr>
                    <td>
                        <img src="{{ asset($slide->image_url) }}" style="width: 120px; height: 60px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border);">
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ is_array($slide->title) ? ($slide->title['en'] ?? '') : $slide->title }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); direction: rtl;">{{ is_array($slide->title) ? ($slide->title['ar'] ?? '') : '' }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $slide->status ? 'badge-success' : 'badge-danger' }}">
                            {{ $slide->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editSlider({{ json_encode($slide) }})" class="btn" style="background: rgba(255, 255, 255, 0.1);">✏️</button>
                            <form action="{{ route('admin.sliders.destroy', $slide->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top: 1rem;">{{ $sliders->links() }}</div>
    </div>

    <!-- Modal -->
    <div id="sliderModal" class="overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Add Slider</h2>
                <button class="close-modal" onclick="closeModal()">✕</button>
            </div>
            
            <form id="sliderForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>
                
                <div style="display: flex; gap: 1rem; margin-bottom: 1.25rem;">
                    <div style="flex: 1;">
                        <label>Title (EN)</label>
                        <input type="text" name="title" id="title" placeholder="e.g. Summer Sale">
                    </div>
                    <div style="flex: 1;">
                        <label style="direction: rtl; display: block;">العنوان (AR)</label>
                        <input type="text" name="title_ar" id="title_ar" dir="rtl" placeholder="مثلاً: عروض الصيف">
                    </div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <label>Banner Image</label>
                    <div style="position: relative;">
                        <input type="file" name="image" id="slideInput" style="display: none;" onchange="previewImage(this)">
                        <div onclick="document.getElementById('slideInput').click()" style="cursor: pointer; border: 2px dashed var(--border); border-radius: 1rem; padding: 1.5rem; text-align: center; background: rgba(255,255,255,0.03); transition: all 0.3s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                            <span id="slideIcon" style="font-size: 2rem;">🖼️</span>
                            <div id="fileName" style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.8rem;">Click to upload banner photo</div>
                            <img id="slidePreview" style="display: none; width: 100%; height: 160px; object-fit: cover; border-radius: 0.75rem; margin-top: 1rem; border: 1px solid var(--border);">
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid var(--border); padding-top: 1rem;">
                    <button type="button" onclick="closeModal()" class="btn" style="background: rgba(255,255,255,0.05); color: var(--text-muted);">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="padding-left: 2rem; padding-right: 2rem;">✨ Save Slider</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(mode) {
            document.getElementById('sliderModal').classList.add('show');
            if (mode === 'add') {
                document.getElementById('modalTitle').innerText = 'Add Slider';
                document.getElementById('sliderForm').action = "{{ route('admin.sliders.store') }}";
                document.getElementById('methodField').innerHTML = '';
                document.getElementById('title').value = '';
                document.getElementById('title_ar').value = '';
                document.getElementById('slideInput').required = true;
                resetPreview();
            }
        }

        function editSlider(slide) {
            document.getElementById('sliderModal').classList.add('show');
            document.getElementById('modalTitle').innerText = 'Edit Slider';
            document.getElementById('sliderForm').action = "sliders/" + slide.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('title').value = typeof slide.title === 'object' ? (slide.title.en || '') : slide.title;
            document.getElementById('title_ar').value = typeof slide.title === 'object' ? (slide.title.ar || '') : '';
            document.getElementById('slideInput').required = false;
            
            if (slide.image_url) {
                document.getElementById('slidePreview').src = "{{ asset('') }}" + slide.image_url;
                document.getElementById('slidePreview').style.display = 'block';
                document.getElementById('slideIcon').style.display = 'none';
            }
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('slidePreview').src = e.target.result;
                    document.getElementById('slidePreview').style.display = 'block';
                    document.getElementById('slideIcon').style.display = 'none';
                    document.getElementById('fileName').innerText = input.files[0].name;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function resetPreview() {
            document.getElementById('slidePreview').style.display = 'none';
            document.getElementById('slideIcon').style.display = 'block';
            document.getElementById('fileName').innerText = 'Click to upload slider';
        }

        function closeModal() {
            document.getElementById('sliderModal').classList.remove('show');
        }
    </script>
@endsection
