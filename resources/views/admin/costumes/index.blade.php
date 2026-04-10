<x-layouts.admin title="Kelola Busana">
    <x-page-header title="Manajemen Data Busana" subtitle="Tambah, edit, dan rapikan stok kostum atau kebaya yang ditampilkan di katalog.">
        <div class="d-flex gap-2">
            @if(!$editingCostume)
                <button type="button" class="btn btn-maroon-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#formBusanaModal">
                    + Tambah Busana Baru
                </button>
            @endif
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark rounded-pill px-4">Kembali ke Dashboard</a>
        </div>
    </x-page-header>

    <!-- Modal Form -->
    <div class="modal fade" id="formBusanaModal" tabindex="-1" aria-labelledby="formBusanaModalLabel" aria-hidden="true" data-bs-backdrop="{{ $editingCostume ? 'static' : 'true' }}">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pt-4 px-4 pb-0">
                    <h5 class="modal-title h4 fw-bold" id="formBusanaModalLabel">{{ $editingCostume ? 'Edit Busana' : 'Tambah Busana Baru' }}</h5>
                    @if($editingCostume)
                        <a href="{{ route('admin.costumes.index') }}" class="btn-close" aria-label="Close"></button>
                    @else
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    @endif
                </div>
                <div class="modal-body p-4">
                    <form action="{{ $editingCostume ? route('admin.costumes.update', $editingCostume) : route('admin.costumes.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                    @if ($editingCostume)
                        @method('PUT')
                    @endif
                    <div class="col-12">
                        <label class="form-label">Nama Busana</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $editingCostume->name ?? '') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category', $editingCostume->category ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" min="0" class="form-control" value="{{ old('stock', $editingCostume->stock ?? 0) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Sewa</label>
                        <input type="number" name="rental_price" min="0" class="form-control" value="{{ old('rental_price', $editingCostume->rental_price ?? 0) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Gambar Busana (Min 1, Max 4)</label>
                        <input type="file" id="imageInput" accept="image/*" multiple class="form-control" {{ !$editingCostume ? 'required' : '' }}>
                        <div id="compressStatus" class="form-text text-muted mt-1" style="display:none;">
                            <span class="spinner-border spinner-border-sm me-1" role="status"></span> Mengompresi gambar...
                        </div>
                        <div class="form-text">Pilih hingga 4 gambar sekaligus atau satu per satu. Gambar akan dikompres otomatis sebelum diunggah.</div>
                        @error('images')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        {{-- Preview strip --}}
                        <div id="imagePreviewStrip" class="d-flex gap-2 flex-wrap mt-2"></div>
                        @if($editingCostume)
                            <div class="mt-2 text-muted" style="font-size: 0.8rem;">
                                Gambar saat ini:
                                <div class="d-flex gap-2 mt-1" id="currentImages">
                                    @foreach(['image', 'image_2', 'image_3', 'image_4'] as $col)
                                        @if($editingCostume->$col)
                                            <img src="{{ asset('storage/' . $editingCostume->$col) }}" alt="Preview" style="max-height: 50px; border-radius: 4px;">
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Ukuran (opsional)</label>
                        <input type="text" name="sizes" class="form-control" placeholder="Contoh: S, M, L, XL" value="{{ old('sizes', $editingCostume->sizes ?? '') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi (opsional)</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $editingCostume->description ?? '') }}</textarea>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Materials & Craftsmanship (opsional)</label>
                        <textarea name="materials" class="form-control" rows="2">{{ old('materials', $editingCostume->materials ?? '') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Care Instructions (opsional)</label>
                        <textarea name="care_instructions" class="form-control" rows="2">{{ old('care_instructions', $editingCostume->care_instructions ?? '') }}</textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                        @if ($editingCostume)
                            <a href="{{ route('admin.costumes.index') }}" class="btn btn-outline-dark rounded-pill px-4">Batal</a>
                        @else
                            <button type="button" class="btn btn-outline-dark rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        @endif
                        <button type="submit" class="btn btn-maroon-primary rounded-pill px-4">{{ $editingCostume ? 'Simpan Perubahan' : 'Tambah Data' }}</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="content-panel">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Terbooking</th>
                                <th>Harga</th>
                                <th>Img</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($costumes as $costume)
                                <tr>
                                    <td class="fw-semibold">{{ $costume->name }}</td>
                                    <td>{{ $costume->category }}</td>
                                    <td>{{ $costume->stock }}</td>
                                    <td>{{ (int) ($costume->total_booked ?? 0) }}</td>
                                    <td>Rp{{ number_format((float) $costume->rental_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($costume->image)
                                            <span class="badge rounded-pill text-bg-info px-2 py-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/><path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/></svg></span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill text-bg-{{ $costume->stock > 0 ? 'success' : 'secondary' }} px-3 py-2">{{ $costume->availability_status }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.costumes.edit', $costume) }}" class="btn btn-sm btn-outline-dark rounded-pill">Edit</a>
                                            <form action="{{ route('admin.costumes.destroy', $costume) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

<script>
(function () {
    const MAX_FILES    = 4;
    const MAX_WIDTH    = 1400;   // px — resize down jika lebih besar
    const QUALITY      = 0.80;   // 80% JPEG
    const MAX_BYTES    = 8 * 1024 * 1024; // 8 MB per file (cocok dengan validasi server)

    const fileInput    = document.getElementById('imageInput');
    const previewStrip = document.getElementById('imagePreviewStrip');
    const statusEl     = document.getElementById('compressStatus');
    const form         = fileInput ? fileInput.closest('form') : null;
    const currentImgs  = document.getElementById('currentImages');

    if (!fileInput || !form) return;

    let selectedImages = [];
    let isCompressing = false;
    fileInput.name = 'images[]';

    fileInput.addEventListener('change', async function () {
        const incomingFiles = Array.from(fileInput.files);
        if (incomingFiles.length === 0) return;

        const availableSlots = MAX_FILES - selectedImages.length;
        if (availableSlots <= 0) {
            alert('Maksimal 4 gambar. Hapus salah satu preview jika ingin mengganti gambar.');
            syncInputFiles();
            return;
        }

        const files = incomingFiles.slice(0, availableSlots);
        if (incomingFiles.length > availableSlots) {
            alert(`Maksimal 4 gambar. Hanya ${availableSlots} gambar yang ditambahkan.`);
        }

        isCompressing = true;
        statusEl.style.display = 'block';

        try {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                let blob;
                if (file.type === 'image/jpeg' && file.size <= MAX_BYTES && await getWidth(file) <= MAX_WIDTH) {
                    blob = file;
                } else {
                    blob = await compressImage(file);
                }

                const compressedFile = new File(
                    [blob],
                    file.name.replace(/\.[^.]+$/, '') + '.jpg',
                    { type: 'image/jpeg' }
                );

                selectedImages.push({
                    file: compressedFile,
                    url: URL.createObjectURL(compressedFile),
                });
            }
        } finally {
            isCompressing = false;
            statusEl.style.display = 'none';
            renderPreviews();
            syncInputFiles();
        }
    });

    form.addEventListener('submit', function (e) {
        if (isCompressing) {
            e.preventDefault();
            alert('Mohon tunggu, gambar sedang dikompres...');
            return;
        }

        syncInputFiles();
    });

    function syncInputFiles() {
        const transfer = new DataTransfer();
        selectedImages.forEach(item => transfer.items.add(item.file));
        fileInput.files = transfer.files;

        if (currentImgs) {
            currentImgs.style.display = selectedImages.length > 0 ? 'none' : '';
        }
    }

    function renderPreviews() {
        previewStrip.innerHTML = '';

        selectedImages.forEach((item, index) => {
            const img = document.createElement('img');
            img.src = item.url;
            img.alt = 'Preview gambar busana';
            img.style.cssText = 'height:60px;width:60px;border-radius:6px;object-fit:cover;border:2px solid #dee2e6;';

            const sizeKB = Math.round(item.file.size / 1024);
            const wrapper = document.createElement('div');
            wrapper.style.cssText = 'position:relative;display:inline-block;';

            const badge = document.createElement('span');
            badge.style.cssText = 'position:absolute;bottom:2px;right:2px;background:rgba(0,0,0,.55);color:#fff;font-size:9px;padding:1px 4px;border-radius:3px;';
            badge.textContent = sizeKB + ' KB';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.setAttribute('aria-label', 'Hapus gambar');
            removeButton.textContent = '×';
            removeButton.style.cssText = 'position:absolute;top:-6px;right:-6px;width:20px;height:20px;border:none;border-radius:999px;background:#8b1e3f;color:#fff;font-size:14px;line-height:20px;padding:0;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,.2);';
            removeButton.addEventListener('click', function () {
                URL.revokeObjectURL(item.url);
                selectedImages.splice(index, 1);
                renderPreviews();
                syncInputFiles();
            });

            wrapper.appendChild(img);
            wrapper.appendChild(badge);
            wrapper.appendChild(removeButton);
            previewStrip.appendChild(wrapper);
        });
    }

    function getWidth(file) {
        return new Promise(resolve => {
            const img = new Image();
            img.onload = () => { URL.revokeObjectURL(img.src); resolve(img.naturalWidth); };
            img.src = URL.createObjectURL(file);
        });
    }

    function compressImage(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    let { naturalWidth: w, naturalHeight: h } = img;
                    if (w > MAX_WIDTH) {
                        h = Math.round(h * MAX_WIDTH / w);
                        w = MAX_WIDTH;
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width  = w;
                    canvas.height = h;
                    canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                    canvas.toBlob(blob => resolve(blob), 'image/jpeg', QUALITY);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    syncInputFiles();
}());

document.addEventListener('DOMContentLoaded', function () {
    @if($errors->any() || $editingCostume)
        const myModalEl = document.getElementById('formBusanaModal');
        if (myModalEl) {
            const modal = new bootstrap.Modal(myModalEl);
            modal.show();
        }
    @endif
});
</script>
