<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Costume;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class CostumeController extends Controller
{
    public function index(): View
    {
        return view('admin.costumes.index', [
            'costumes' => Costume::query()
                ->withSum('rentalDetails as total_booked', 'quantity')
                ->orderBy('category')
                ->orderBy('name')
                ->get(),
            'editingCostume' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Deteksi jika request di-drop oleh PHP karena post_max_size terlampaui
        if ($request->isMethod('post') && empty($_FILES) && empty($_POST) && $request->server('CONTENT_LENGTH') > 0) {
            return back()->withErrors(['images' => 'Ukuran total gambar terlalu besar. Harap kompres gambar sebelum mengunggah (maks. 8MB per gambar, total maks. 32MB).'])->withInput();
        }

        $validated = $this->validateCostume($request);

        if ($request->hasFile('images')) {
            $uploadedImages = $request->file('images');
            $columns = ['image', 'image_2', 'image_3', 'image_4'];
            foreach ($uploadedImages as $index => $file) {
                if (isset($columns[$index])) {
                    $validated[$columns[$index]] = $file->store('costumes', 'public');
                }
            }
        }
        unset($validated['images']);

        Costume::query()->create($validated + [
            'availability_status' => $validated['stock'] > 0 ? 'Available' : 'Out of Stock',
        ]);

        return redirect()
            ->route('admin.costumes.index')
            ->with('success', 'Katalog busana berhasil ditambahkan.');
    }

    public function edit(Costume $costume): View
    {
        return view('admin.costumes.index', [
            'costumes' => Costume::query()
                ->withSum('rentalDetails as total_booked', 'quantity')
                ->orderBy('category')
                ->orderBy('name')
                ->get(),
            'editingCostume' => $costume,
        ]);
    }

    public function update(Request $request, Costume $costume): RedirectResponse
    {
        // Deteksi jika request di-drop oleh PHP karena post_max_size terlampaui
        if ($request->isMethod('post') && empty($_FILES) && empty($_POST) && $request->server('CONTENT_LENGTH') > 0) {
            return back()->withErrors(['images' => 'Ukuran total gambar terlalu besar. Harap kompres gambar sebelum mengunggah (maks. 8MB per gambar, total maks. 32MB).'])->withInput();
        }

        $validated = $this->validateCostume($request);

        if ($request->hasFile('images')) {
            $columns = ['image', 'image_2', 'image_3', 'image_4'];
            // Hapus gambar lama
            foreach ($columns as $col) {
                if ($costume->$col) {
                    Storage::disk('public')->delete($costume->$col);
                }
                $validated[$col] = null; // Reset semua kolom gambar
            }

            // Simpan gambar baru
            $uploadedImages = $request->file('images');
            foreach ($uploadedImages as $index => $file) {
                if (isset($columns[$index])) {
                    $validated[$columns[$index]] = $file->store('costumes', 'public');
                }
            }
        }
        unset($validated['images']);

        $costume->update($validated + [
            'availability_status' => $validated['stock'] > 0 ? 'Available' : 'Out of Stock',
        ]);

        return redirect()
            ->route('admin.costumes.index')
            ->with('success', 'Data busana berhasil diperbarui.');
    }

    public function destroy(Costume $costume): RedirectResponse
    {
        try {
            $imagePaths = array_filter([$costume->image, $costume->image_2, $costume->image_3, $costume->image_4]);
            $costume->delete();
            foreach ($imagePaths as $path) {
                Storage::disk('public')->delete($path);
            }
        } catch (QueryException) {
            return back()->with('error', 'Busana tidak bisa dihapus karena sudah terhubung dengan transaksi.');
        }

        return back()->with('success', 'Data busana berhasil dihapus.');
    }

    private function validateCostume(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'rental_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'materials' => ['nullable', 'string'],
            'care_instructions' => ['nullable', 'string'],
            'sizes' => ['nullable', 'string', 'max:255'],
            'images' => [$request->isMethod('post') ? 'required' : 'nullable', 'array', 'min:1', 'max:4'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:8192'], // 8MB per file
        ]);
    }
}
