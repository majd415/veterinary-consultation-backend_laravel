@extends('admin.layout')

@section('title', __('admin.products'))

@section('content')
    <div style="margin-bottom: 2rem;">
        <button onclick="switchTab('products')" id="tab-products" class="btn btn-primary">{{ __('admin.products') }}</button>
        <button onclick="switchTab('categories')" id="tab-categories" class="btn" style="background: transparent; border: 1px solid var(--border); color: var(--text-muted);">{{ __('admin.service_prices') }}</button>
    </div>

    <!-- Products Section -->
    <div id="view-products">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
            <button onclick="openProductModal('add')" class="btn btn-primary">+ {{ __('admin.add_new') }}</button>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                    <th>📸</th>
                    <th>{{ __('admin.name') }}</th>
                    <th>{{ __('admin.role') }}</th>
                    <th>{{ __('admin.total_revenue') }}</th>
                    <th>{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border);">
                        @else
                            <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 8px;"></div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ is_array($product->name) ? ($product->name['en'] ?? '') : $product->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); direction: rtl;">{{ is_array($product->name) ? ($product->name['ar'] ?? '') : '' }}</div>
                    </td>
                    <td>{{ is_array($product->category->name) ? ($product->category->name['en'] ?? '') : $product->category->name }}</td>
                    <td>${{ $product->price }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editProduct({{ json_encode($product) }})" class="btn" style="background: rgba(255, 255, 255, 0.1); color: var(--text);">✏️</button>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top: 1rem;">{{ $products->links() }}</div>
    </div>
</div>

<!-- Categories Section -->
<div id="view-categories" style="display: none;">
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
        <button onclick="document.getElementById('catModal').classList.add('show')" class="btn btn-primary">+ Add Category</button>
    </div>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    <td>{{ is_array($cat->name) ? ($cat->name['en'] ?? '') : $cat->name }}</td>
                    <td>
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Product Modal -->
<div id="productModal" class="overlay">
    <div class="modal-card">
        <div class="modal-header">
            <h2 class="modal-title" id="pModalTitle">{{ __('admin.add_new') }}</h2>
            <button class="close-modal" onclick="document.getElementById('productModal').classList.remove('show')">✕</button>
        </div>
        
        <form id="productForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="pMethodField"></div>

            <div style="display: flex; gap: 1rem; margin-bottom: 1.25rem;">
                <div style="flex: 1;">
                    <label>Product Name (EN)</label>
                    <input type="text" name="name" id="p_name" required placeholder="e.g. Premium Dog Food">
                </div>
                <div style="flex: 1;">
                    <label style="direction: rtl; display: block;">الاسم (AR)</label>
                    <input type="text" name="name_ar" id="p_name_ar" required dir="rtl" placeholder="مثلاً: طعام كلاب فاخر">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label>Product Image</label>
                <div style="position: relative;">
                    <input type="file" name="image" id="p_imageInput" style="display: none;" onchange="previewProductImg(this)">
                    <div onclick="document.getElementById('p_imageInput').click()" style="cursor: pointer; border: 2px dashed var(--border); border-radius: 1rem; padding: 1.25rem; text-align: center; background: rgba(255,255,255,0.03); transition: all 0.3s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                        <span id="p_imageIcon" style="font-size: 2rem;">🖼️</span>
                        <div id="p_fileName" style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.8rem;">Drop image here or click to upload</div>
                        <img id="p_imagePreview" style="display: none; width: 100%; height: 140px; object-fit: cover; border-radius: 0.75rem; margin-top: 1rem; border: 1px solid var(--border);">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-bottom: 1.25rem;">
                <div style="flex: 1;">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" id="p_price" required placeholder="0.00">
                </div>
                <div style="flex: 1;">
                    <label>Category</label>
                    <select name="category_id" id="p_category">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ is_array($cat->name) ? ($cat->name['en'] ?? '') : $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label>Description (EN)</label>
                <textarea name="description" id="p_desc" rows="3" placeholder="Enter product details..."></textarea>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="direction: rtl; display: block;">الوصف (AR)</label>
                <textarea name="description_ar" id="p_desc_ar" rows="3" dir="rtl" placeholder="أدخل تفاصيل المنتج..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                <button type="button" onclick="document.getElementById('productModal').classList.remove('show')" class="btn" style="background: rgba(255,255,255,0.05); color: var(--text-muted);">{{ __('admin.cancel') }}</button>
                <button type="submit" class="btn btn-primary" style="padding-left: 2rem; padding-right: 2rem;">✨ {{ __('admin.save') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Category Modal -->
<div id="catModal" class="overlay">
    <div class="modal-card" style="max-width: 400px; margin-top: 10vh;">
        <div class="modal-header">
            <h2 class="modal-title">New Category</h2>
            <button class="close-modal" onclick="document.getElementById('catModal').classList.remove('show')">✕</button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 2rem;">
                <label>Category Name (English)</label>
                <input type="text" name="name" required placeholder="e.g. Toys">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" onclick="document.getElementById('catModal').classList.remove('show')" class="btn" style="background: rgba(255,255,255,0.05); color: var(--text-muted);">Cancel</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTab(tab) {
        document.getElementById('view-products').style.display = tab === 'products' ? 'block' : 'none';
        document.getElementById('view-categories').style.display = tab === 'categories' ? 'block' : 'none';
        
        document.getElementById('tab-products').className = tab === 'products' ? 'btn btn-primary' : 'btn';
        document.getElementById('tab-products').style.color = tab === 'products' ? 'white' : 'var(--text-muted)';
        
        document.getElementById('tab-categories').className = tab === 'categories' ? 'btn btn-primary' : 'btn';
         document.getElementById('tab-categories').style.color = tab === 'categories' ? 'white' : 'var(--text-muted)';
    }

    function previewProductImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('p_imagePreview').src = e.target.result;
                document.getElementById('p_imagePreview').style.display = 'block';
                document.getElementById('p_imageIcon').style.display = 'none';
                document.getElementById('p_fileName').innerText = input.files[0].name;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openProductModal(mode) {
        document.getElementById('productModal').classList.add('show');
        if (mode === 'add') {
            document.getElementById('pModalTitle').innerText = "{{ __('admin.add_new') }}";
            document.getElementById('productForm').action = "{{ route('admin.products.store') }}";
            document.getElementById('pMethodField').innerHTML = '';
            document.getElementById('p_name').value = '';
            document.getElementById('p_name_ar').value = '';
            document.getElementById('p_price').value = '';
            document.getElementById('p_desc').value = '';
            document.getElementById('p_desc_ar').value = '';
            document.getElementById('p_imagePreview').style.display = 'none';
            document.getElementById('p_imageIcon').style.display = 'block';
            document.getElementById('p_fileName').innerText = 'Click to upload product photo';
        }
    }

    function editProduct(product) {
        document.getElementById('productModal').classList.add('show');
        document.getElementById('pModalTitle').innerText = "{{ __('admin.edit') }}";
        document.getElementById('productForm').action = "products/" + product.id;
        document.getElementById('pMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('p_name').value = typeof product.name === 'object' ? (product.name.en || '') : product.name;
        document.getElementById('p_name_ar').value = typeof product.name === 'object' ? (product.name.ar || '') : '';
        document.getElementById('p_price').value = product.price;
        document.getElementById('p_category').value = product.category_id;
        
        document.getElementById('p_desc').value = typeof product.description === 'object' ? (product.description.en || '') : (product.description || '');
        document.getElementById('p_desc_ar').value = typeof product.description === 'object' ? (product.description.ar || '') : '';

        if (product.image) {
            document.getElementById('p_imagePreview').src = "{{ asset('') }}" + product.image;
            document.getElementById('p_imagePreview').style.display = 'block';
            document.getElementById('p_imageIcon').style.display = 'none';
            document.getElementById('p_fileName').innerText = 'Change current photo';
        } else {
            document.getElementById('p_imagePreview').style.display = 'none';
            document.getElementById('p_imageIcon').style.display = 'block';
        }
    }
</script>
@endsection
