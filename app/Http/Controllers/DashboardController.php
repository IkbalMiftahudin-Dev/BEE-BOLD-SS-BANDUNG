<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\AgeProgram;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\RegistrationFee;
use App\Models\TrainingSchedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index', [
            'stats' => [
                'news_total' => News::count(),
                'news_published' => News::where('is_published', true)->count(),
                'categories' => NewsCategory::count(),
                'galleries' => Gallery::count(),
                'galleries_home' => Gallery::where('is_highlighted_on_home', true)->count(),
                'programs' => AgeProgram::where('is_active', true)->count(),
                'schedules' => TrainingSchedule::count(),
                'faqs' => Faq::where('is_active', true)->count(),
                'fees' => RegistrationFee::where('is_active', true)->count(),
                'editors' => User::where('role', UserRole::Editor)->count(),
            ],
            'latestNews' => News::with(['category', 'author'])->orderByDesc('created_at')->limit(5)->get(),
            'latestGalleries' => Gallery::with('uploader')->orderByDesc('created_at')->limit(6)->get(),
        ]);
    }

    // =========================================================================
    // BERITA (Admin & Editor)
    // =========================================================================

    public function newsIndex(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $categoryId = $request->integer('category_id') ?: null;

        $news = News::query()
            ->with(['category', 'author'])
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($search !== '', function ($q) use ($search) {
                $term = '%'.addcslashes($search, '\\%_').'%';
                $q->where(fn ($inner) => $inner
                    ->where('title', 'like', $term)
                    ->orWhere('lead_paragraph', 'like', $term));
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.news.index', [
            'news' => $news,
            'categories' => NewsCategory::orderBy('name')->get(),
            'search' => $search,
            'activeCategoryId' => $categoryId,
        ]);
    }

    public function newsCreate(): View
    {
        return view('dashboard.news.form', [
            'article' => new News([
                'is_published' => true,
                'is_featured' => false,
                'published_at' => now(),
            ]),
            'categories' => NewsCategory::orderBy('name')->get(),
            'isEdit' => false,
        ]);
    }

    public function newsStore(Request $request): RedirectResponse
    {
        $validated = $this->validateNews($request);
        $thumbnailPath = $this->resolveUploadedOrUrlImage($request, 'thumbnail_file', 'thumbnail_url', 'news');

        if ($thumbnailPath === null) {
            throw ValidationException::withMessages([
                'thumbnail_file' => 'Unggah berkas foto utama atau masukkan URL gambar yang valid.',
            ]);
        }

        if ($validated['is_featured']) {
            News::where('is_featured', true)->update(['is_featured' => false]);
        }

        $article = new News([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $this->uniqueNewsSlug($validated['title']),
            'lead_paragraph' => $validated['lead_paragraph'],
            'content' => $validated['content'],
            'thumbnail_path' => $thumbnailPath,
            'is_featured' => $validated['is_featured'],
            'is_published' => $validated['is_published'],
            'published_at' => $validated['published_at'],
        ]);
        $article->author_id = (int) $request->user()->id;
        $article->save();

        return redirect()->route('dashboard.news.index')->with('status', 'Berita berhasil ditambahkan.');
    }

    public function newsEdit(News $news): View
    {
        return view('dashboard.news.form', [
            'article' => $news,
            'categories' => NewsCategory::orderBy('name')->get(),
            'isEdit' => true,
        ]);
    }

    public function newsUpdate(Request $request, News $news): RedirectResponse
    {
        $validated = $this->validateNews($request, $news);
        $thumbnailPath = $this->resolveUploadedOrUrlImage(
            $request,
            'thumbnail_file',
            'thumbnail_url',
            'news',
            $news->thumbnail_path,
        );

        if ($validated['is_featured'] && ! $news->is_featured) {
            News::where('is_featured', true)->whereKeyNot($news->getKey())->update(['is_featured' => false]);
        }

        $slug = $news->title !== $validated['title']
            ? $this->uniqueNewsSlug($validated['title'], $news->id)
            : $news->slug;

        $news->fill([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'lead_paragraph' => $validated['lead_paragraph'],
            'content' => $validated['content'],
            'thumbnail_path' => $thumbnailPath,
            'is_featured' => $validated['is_featured'],
            'is_published' => $validated['is_published'],
            'published_at' => $validated['published_at'],
        ])->save();

        return redirect()->route('dashboard.news.index')->with('status', 'Berita berhasil diperbarui.');
    }

    public function newsDestroy(News $news): RedirectResponse
    {
        $news->delete();

        return redirect()->route('dashboard.news.index')->with('status', 'Berita berhasil dihapus.');
    }

    // =========================================================================
    // KATEGORI BERITA (Admin & Editor)
    // =========================================================================

    public function categoriesIndex(): View
    {
        return view('dashboard.categories.index', [
            'categories' => NewsCategory::withCount('news')->orderBy('id')->get(),
        ]);
    }

    public function categoriesStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
        ]);

        $slug = Str::slug($validated['name']);
        if ($slug === '' || NewsCategory::where('slug', $slug)->exists()) {
            throw ValidationException::withMessages([
                'name' => 'Kategori dengan nama/slug tersebut sudah ada.',
            ]);
        }

        NewsCategory::create([
            'name' => trim($validated['name']),
            'slug' => $slug,
        ]);

        return redirect()->route('dashboard.categories.index')->with('status', 'Kategori berita berhasil ditambahkan.');
    }

    public function categoriesUpdate(Request $request, NewsCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $slug = Str::slug($validated['name']);
        if ($slug === '' || NewsCategory::where('slug', $slug)->whereKeyNot($category->getKey())->exists()) {
            throw ValidationException::withMessages([
                'name' => 'Kategori dengan nama/slug tersebut sudah digunakan.',
            ]);
        }

        $category->update([
            'name' => trim($validated['name']),
            'slug' => $slug,
        ]);

        return redirect()->route('dashboard.categories.index')->with('status', 'Kategori berita berhasil diperbarui.');
    }

    public function categoriesDestroy(NewsCategory $category): RedirectResponse
    {
        if ($category->news()->exists()) {
            return redirect()
                ->route('dashboard.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh artikel berita.');
        }

        $category->delete();

        return redirect()->route('dashboard.categories.index')->with('status', 'Kategori berita berhasil dihapus.');
    }

    // =========================================================================
    // GALERI (Admin & Editor)
    // =========================================================================

    public function galleriesIndex(): View
    {
        return view('dashboard.galleries.index', [
            'galleries' => Gallery::with('uploader')
                ->orderByDesc('is_highlighted_on_home')
                ->orderByDesc('created_at')
                ->orderBy('id')
                ->paginate(12),
        ]);
    }

    public function galleriesCreate(): View
    {
        return view('dashboard.galleries.form', [
            'gallery' => new Gallery([
                'grid_ratio' => 'landscape',
                'is_highlighted_on_home' => false,
            ]),
            'isEdit' => false,
        ]);
    }

    public function galleriesStore(Request $request): RedirectResponse
    {
        $validated = $this->validateGallery($request);
        $imagePath = $this->resolveUploadedOrUrlImage($request, 'image_file', 'image_url', 'galleries');

        if ($imagePath === null) {
            throw ValidationException::withMessages([
                'image_file' => 'Unggah berkas foto galeri atau masukkan URL gambar yang valid.',
            ]);
        }

        $gallery = new Gallery([
            'title' => $validated['title'],
            'caption' => $validated['caption'],
            'image_path' => $imagePath,
            'grid_ratio' => $validated['grid_ratio'],
            'is_highlighted_on_home' => $validated['is_highlighted_on_home'],
        ]);
        $gallery->uploaded_by = (int) $request->user()->id;
        $gallery->save();

        return redirect()->route('dashboard.galleries.index')->with('status', 'Foto galeri berhasil ditambahkan.');
    }

    public function galleriesEdit(Gallery $gallery): View
    {
        return view('dashboard.galleries.form', [
            'gallery' => $gallery,
            'isEdit' => true,
        ]);
    }

    public function galleriesUpdate(Request $request, Gallery $gallery): RedirectResponse
    {
        $validated = $this->validateGallery($request, $gallery);
        $imagePath = $this->resolveUploadedOrUrlImage(
            $request,
            'image_file',
            'image_url',
            'galleries',
            $gallery->image_path,
        );

        $gallery->fill([
            'title' => $validated['title'],
            'caption' => $validated['caption'],
            'image_path' => $imagePath,
            'grid_ratio' => $validated['grid_ratio'],
            'is_highlighted_on_home' => $validated['is_highlighted_on_home'],
        ])->save();

        return redirect()->route('dashboard.galleries.index')->with('status', 'Foto galeri berhasil diperbarui.');
    }

    public function galleriesDestroy(Gallery $gallery): RedirectResponse
    {
        $gallery->delete();

        return redirect()->route('dashboard.galleries.index')->with('status', 'Foto galeri berhasil dihapus.');
    }

    // =========================================================================
    // PROGRAM USIA (Khusus Admin)
    // =========================================================================

    public function programsIndex(): View
    {
        return view('dashboard.programs.index', [
            'programs' => AgeProgram::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function programsCreate(): View
    {
        return view('dashboard.programs.form', [
            'program' => new AgeProgram([
                'category_group' => 'Grassroot',
                'sort_order' => (int) AgeProgram::max('sort_order') + 1,
                'is_active' => true,
            ]),
            'isEdit' => false,
        ]);
    }

    public function programsStore(Request $request): RedirectResponse
    {
        $validated = $this->validateProgram($request);
        $imagePath = $this->resolveUploadedOrUrlImage($request, 'image_file', 'image_url', 'programs');

        if ($imagePath === null) {
            throw ValidationException::withMessages([
                'image_file' => 'Unggah berkas foto program atau masukkan URL gambar yang valid.',
            ]);
        }

        AgeProgram::create($validated + ['image_path' => $imagePath]);

        return redirect()->route('dashboard.programs.index')->with('status', 'Program usia berhasil ditambahkan.');
    }

    public function programsEdit(AgeProgram $program): View
    {
        return view('dashboard.programs.form', [
            'program' => $program,
            'isEdit' => true,
        ]);
    }

    public function programsUpdate(Request $request, AgeProgram $program): RedirectResponse
    {
        $validated = $this->validateProgram($request, $program);
        $imagePath = $this->resolveUploadedOrUrlImage(
            $request,
            'image_file',
            'image_url',
            'programs',
            $program->image_path,
        );

        $program->update($validated + ['image_path' => $imagePath]);

        return redirect()->route('dashboard.programs.index')->with('status', 'Program usia berhasil diperbarui.');
    }

    public function programsDestroy(AgeProgram $program): RedirectResponse
    {
        $program->delete();

        return redirect()->route('dashboard.programs.index')->with('status', 'Program usia berhasil dihapus.');
    }

    // =========================================================================
    // JADWAL LATIHAN (Khusus Admin)
    // =========================================================================

    public function schedulesIndex(): View
    {
        return view('dashboard.schedules.index', [
            'schedulesByDay' => TrainingSchedule::with('ageProgram')
                ->orderBy('id')
                ->get()
                ->groupBy('day_of_week'),
            'days' => array_keys(TrainingSchedule::DAYS),
        ]);
    }

    public function schedulesCreate(): View
    {
        return view('dashboard.schedules.form', [
            'schedule' => new TrainingSchedule([
                'day_of_week' => 'Senin',
                'time_slot' => '16:00 – 18:00',
                'activity_type' => 'Grassroot',
            ]),
            'programs' => AgeProgram::orderBy('sort_order')->get(),
            'days' => array_keys(TrainingSchedule::DAYS),
            'activityTypes' => TrainingSchedule::ACTIVITY_TYPES,
            'isEdit' => false,
        ]);
    }

    public function schedulesStore(Request $request): RedirectResponse
    {
        $validated = $this->validateSchedule($request);
        TrainingSchedule::create($validated);

        return redirect()->route('dashboard.schedules.index')->with('status', 'Jadwal latihan berhasil ditambahkan.');
    }

    public function schedulesEdit(TrainingSchedule $schedule): View
    {
        return view('dashboard.schedules.form', [
            'schedule' => $schedule,
            'programs' => AgeProgram::orderBy('sort_order')->get(),
            'days' => array_keys(TrainingSchedule::DAYS),
            'activityTypes' => TrainingSchedule::ACTIVITY_TYPES,
            'isEdit' => true,
        ]);
    }

    public function schedulesUpdate(Request $request, TrainingSchedule $schedule): RedirectResponse
    {
        $validated = $this->validateSchedule($request);
        $schedule->update($validated);

        return redirect()->route('dashboard.schedules.index')->with('status', 'Jadwal latihan berhasil diperbarui.');
    }

    public function schedulesDestroy(TrainingSchedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('dashboard.schedules.index')->with('status', 'Jadwal latihan berhasil dihapus.');
    }

    // =========================================================================
    // FAQ (Khusus Admin)
    // =========================================================================

    public function faqsIndex(): View
    {
        return view('dashboard.faqs.index', [
            'faqs' => Faq::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function faqsCreate(): View
    {
        return view('dashboard.faqs.form', [
            'faq' => new Faq([
                'sort_order' => (int) Faq::max('sort_order') + 1,
                'is_active' => true,
            ]),
            'isEdit' => false,
        ]);
    }

    public function faqsStore(Request $request): RedirectResponse
    {
        $validated = $this->validateFaq($request);
        Faq::create($validated);

        return redirect()->route('dashboard.faqs.index')->with('status', 'Item FAQ berhasil ditambahkan.');
    }

    public function faqsEdit(Faq $faq): View
    {
        return view('dashboard.faqs.form', [
            'faq' => $faq,
            'isEdit' => true,
        ]);
    }

    public function faqsUpdate(Request $request, Faq $faq): RedirectResponse
    {
        $validated = $this->validateFaq($request);
        $faq->update($validated);

        return redirect()->route('dashboard.faqs.index')->with('status', 'Item FAQ berhasil diperbarui.');
    }

    public function faqsDestroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('dashboard.faqs.index')->with('status', 'Item FAQ berhasil dihapus.');
    }

    // =========================================================================
    // BIAYA PENDAFTARAN (Khusus Admin)
    // =========================================================================

    public function feesIndex(): View
    {
        return view('dashboard.fees.index', [
            'fees' => RegistrationFee::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function feesCreate(): View
    {
        return view('dashboard.fees.form', [
            'fee' => new RegistrationFee([
                'fee_type' => RegistrationFee::TYPE_MONTHLY,
                'sort_order' => (int) RegistrationFee::max('sort_order') + 1,
                'is_active' => true,
            ]),
            'isEdit' => false,
        ]);
    }

    public function feesStore(Request $request): RedirectResponse
    {
        $validated = $this->validateFee($request);
        RegistrationFee::create($validated);

        return redirect()->route('dashboard.fees.index')->with('status', 'Komponen biaya pendaftaran berhasil ditambahkan.');
    }

    public function feesEdit(RegistrationFee $fee): View
    {
        return view('dashboard.fees.form', [
            'fee' => $fee,
            'isEdit' => true,
        ]);
    }

    public function feesUpdate(Request $request, RegistrationFee $fee): RedirectResponse
    {
        $validated = $this->validateFee($request);
        $fee->update($validated);

        return redirect()->route('dashboard.fees.index')->with('status', 'Komponen biaya pendaftaran berhasil diperbarui.');
    }

    public function feesDestroy(RegistrationFee $fee): RedirectResponse
    {
        $fee->delete();

        return redirect()->route('dashboard.fees.index')->with('status', 'Komponen biaya pendaftaran berhasil dihapus.');
    }

    // =========================================================================
    // KELOLA EDITOR (Khusus Admin)
    // =========================================================================

    public function editorsIndex(): View
    {
        return view('dashboard.editors.index', [
            'editors' => User::query()
                ->where('role', UserRole::Editor)
                ->withCount(['news', 'galleries'])
                ->orderByDesc('created_at')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function editorsCreate(): View
    {
        return view('dashboard.editors.form', [
            'editor' => new User,
            'isEdit' => false,
        ]);
    }

    public function editorsStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap editor wajib diisi.',
            'email.required' => 'Alamat email editor wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $editor = new User([
            'name' => trim($validated['name']),
            'email' => Str::lower(trim($validated['email'])),
            'password' => $validated['password'],
        ]);
        $editor->forceFill([
            'role' => UserRole::Editor,
            'email_verified_at' => now(),
        ])->save();

        return redirect()->route('dashboard.editors.index')->with('status', 'Akun editor baru berhasil ditambahkan.');
    }

    public function editorsEdit(User $editor): View
    {
        abort_unless($editor->isEditor(), 404);

        return view('dashboard.editors.form', [
            'editor' => $editor,
            'isEdit' => true,
        ]);
    }

    public function editorsUpdate(Request $request, User $editor): RedirectResponse
    {
        abort_unless($editor->isEditor(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')->ignore($editor->id)],
            'password' => ['nullable', 'string', 'min:8', 'max:255', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap editor wajib diisi.',
            'email.required' => 'Alamat email editor wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $editor->name = trim($validated['name']);
        $editor->email = Str::lower(trim($validated['email']));

        if (! empty($validated['password'])) {
            $editor->password = $validated['password'];
        }

        $editor->save();

        return redirect()->route('dashboard.editors.index')->with('status', 'Data akun editor berhasil diperbarui.');
    }

    public function editorsDestroy(Request $request, User $editor): RedirectResponse
    {
        abort_unless($editor->isEditor(), 404);

        DB::transaction(function () use ($request, $editor) {
            $adminId = (int) $request->user()->id;
            News::where('author_id', $editor->id)->update(['author_id' => $adminId]);
            Gallery::where('uploaded_by', $editor->id)->update(['uploaded_by' => $adminId]);
            $editor->delete();
        });

        return redirect()->route('dashboard.editors.index')->with('status', 'Akun editor berhasil dihapus.');
    }

    // =========================================================================
    // PENGATURAN PROFIL (Admin & Editor)
    // =========================================================================

    public function profileEdit(Request $request): View
    {
        return view('dashboard.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function profileUpdate(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
        ]);

        $user->update([
            'name' => trim($validated['name']),
            'email' => Str::lower(trim($validated['email'])),
        ]);

        return redirect()->route('dashboard.profile.edit')->with('status', 'Profil dan alamat email berhasil diperbarui.');
    }

    public function passwordUpdate(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Kata sandi saat ini yang Anda masukkan tidak sesuai.',
            ]);
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()->route('dashboard.profile.edit')->with('status', 'Kata sandi Anda berhasil diperbarui.');
    }

    // =========================================================================
    // PRIVATE VALIDATORS & HELPERS
    // =========================================================================

    /**
     * @return array<string, mixed>
     */
    private function validateNews(Request $request, ?News $existing = null): array
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:news_categories,id'],
            'title' => ['required', 'string', 'max:200'],
            'lead_paragraph' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'thumbnail_url' => ['nullable', 'string', 'max:255'],
        ], [
            'category_id.required' => 'Kategori berita wajib dipilih.',
            'title.required' => 'Judul berita wajib diisi.',
            'lead_paragraph.required' => 'Paragraf pembuka (ringkasan) wajib diisi.',
            'content.required' => 'Isi lengkap artikel berita wajib diisi.',
            'thumbnail_file.image' => 'Berkas yang diunggah harus berupa gambar.',
            'thumbnail_file.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'thumbnail_file.max' => 'Ukuran berkas gambar maksimal 4 MB.',
        ]);

        $isPublished = $request->boolean('is_published');
        $publishedAt = $existing?->published_at ?? now();

        return [
            'category_id' => (int) $validated['category_id'],
            'title' => trim($validated['title']),
            'lead_paragraph' => trim($validated['lead_paragraph']),
            'content' => trim($validated['content']),
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateGallery(Request $request, ?Gallery $existing = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'caption' => ['nullable', 'string', 'max:255'],
            'grid_ratio' => ['required', Rule::in(['landscape', 'portrait', 'square'])],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_url' => ['nullable', 'string', 'max:255'],
        ], [
            'title.required' => 'Judul momen dokumentasi wajib diisi.',
            'grid_ratio.required' => 'Rasio grid foto wajib dipilih.',
            'image_file.image' => 'Berkas yang diunggah harus berupa gambar.',
            'image_file.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'image_file.max' => 'Ukuran berkas gambar maksimal 4 MB.',
        ]);

        return [
            'title' => trim($validated['title']),
            'caption' => isset($validated['caption']) ? trim((string) $validated['caption']) : null,
            'grid_ratio' => $validated['grid_ratio'],
            'is_highlighted_on_home' => $request->boolean('is_highlighted_on_home'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateProgram(Request $request, ?AgeProgram $existing = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'category_group' => ['required', Rule::in(['Grassroot', 'Youth'])],
            'age_range' => ['required', 'string', 'max:50'],
            'coach_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_url' => ['nullable', 'string', 'max:255'],
        ]);

        return [
            'title' => trim($validated['title']),
            'category_group' => $validated['category_group'],
            'age_range' => trim($validated['age_range']),
            'coach_name' => trim($validated['coach_name']),
            'description' => isset($validated['description']) ? trim((string) $validated['description']) : '',
            'sort_order' => (int) $validated['sort_order'],
            'is_active' => $request->boolean('is_active'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateSchedule(Request $request): array
    {
        $validated = $request->validate([
            'age_program_id' => ['nullable', 'integer', 'exists:age_programs,id'],
            'day_of_week' => ['required', Rule::in(array_keys(TrainingSchedule::DAYS))],
            'time_slot' => ['required', 'string', 'max:50'],
            'target_category' => ['required', 'string', 'max:100'],
            'activity_type' => ['required', Rule::in(TrainingSchedule::ACTIVITY_TYPES)],
            'coach_name' => ['required', 'string', 'max:150'],
        ]);

        return [
            'age_program_id' => ! empty($validated['age_program_id']) ? (int) $validated['age_program_id'] : null,
            'day_of_week' => $validated['day_of_week'],
            'time_slot' => trim($validated['time_slot']),
            'target_category' => trim($validated['target_category']),
            'activity_type' => $validated['activity_type'],
            'coach_name' => trim($validated['coach_name']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateFaq(Request $request): array
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string', 'max:3000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ]);

        return [
            'question' => trim($validated['question']),
            'answer' => trim($validated['answer']),
            'sort_order' => (int) $validated['sort_order'],
            'is_active' => $request->boolean('is_active'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateFee(Request $request): array
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'amount_text' => ['required', 'string', 'max:100'],
            'fee_type' => ['required', Rule::in([RegistrationFee::TYPE_ONE_TIME, RegistrationFee::TYPE_MONTHLY])],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ]);

        return [
            'label' => trim($validated['label']),
            'amount_text' => trim($validated['amount_text']),
            'fee_type' => $validated['fee_type'],
            'sort_order' => (int) $validated['sort_order'],
            'is_active' => $request->boolean('is_active'),
        ];
    }

    /**
     * Stores an uploaded image under public/images/uploads/{folder} with a random UUID filename
     * or validates an external https/http/local image path.
     */
    private function resolveUploadedOrUrlImage(
        Request $request,
        string $fileKey,
        string $urlKey,
        string $folder,
        ?string $fallback = null,
    ): ?string {
        if ($request->hasFile($fileKey)) {
            /** @var UploadedFile $file */
            $file = $request->file($fileKey);
            $extension = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'jpg');
            $safeExtension = in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true) ? $extension : 'jpg';
            $filename = Str::uuid()->toString().'.'.$safeExtension;
            $targetDir = public_path('images/uploads/'.$folder);

            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $file->move($targetDir, $filename);

            return 'images/uploads/'.$folder.'/'.$filename;
        }

        $rawUrl = trim((string) $request->input($urlKey, ''));
        if ($rawUrl !== '') {
            if (Str::startsWith($rawUrl, ['https://', 'http://', 'images/'])) {
                return $rawUrl;
            }

            throw ValidationException::withMessages([
                $urlKey => 'URL gambar harus diawali https://, http://, atau path lokal images/.',
            ]);
        }

        return $fallback;
    }

    private function uniqueNewsSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'berita-bee-bold';
        $slug = $base;
        $counter = 2;

        while (
            News::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}

