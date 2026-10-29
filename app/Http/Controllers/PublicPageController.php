<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhatsAppRegistrationRequest;
use App\Models\AgeProgram;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\RegistrationFee;
use App\Models\TrainingSchedule;
use App\Services\WhatsAppRegistrationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    private const NEWS_PER_PAGE = 9;

    private const GALLERY_PER_PAGE = 24;

    private const HOME_GALLERY_LIMIT = 6;

    public function __construct(private readonly WhatsAppRegistrationService $whatsAppService) {}

    public function index(): View
    {
        $featuredNews = $this->featuredNewsQuery()->first();

        $sideNews = News::query()
            ->published()
            ->with('category')
            ->when($featuredNews, fn (Builder $query) => $query->whereKeyNot($featuredNews->getKey()))
            ->latestPublished()
            ->limit(3)
            ->get();

        $fees = RegistrationFee::query()->active()->get();

        return view('homepage', [
            'programs' => AgeProgram::query()->active()->get(),
            'schedulesByDay' => $this->schedulesByDay(),
            'featuredNews' => $featuredNews,
            'sideNews' => $sideNews,
            'highlightedGalleries' => Gallery::query()
                ->where('is_highlighted_on_home', true)
                ->orderByDesc('created_at')
                ->orderBy('id')
                ->limit(self::HOME_GALLERY_LIMIT)
                ->get(),
            'faqs' => Faq::query()->active()->get(),
            'oneTimeFees' => $fees->where('fee_type', RegistrationFee::TYPE_ONE_TIME)->values(),
            'monthlyFees' => $fees->where('fee_type', RegistrationFee::TYPE_MONTHLY)->values(),
        ]);
    }

    public function allNews(Request $request): View
    {
        $filters = $request->validate([
            'kategori' => ['nullable', 'string', 'max:60', 'alpha_dash'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $categorySlug = $filters['kategori'] ?? null;
        $search = trim((string) ($filters['q'] ?? ''));
        $isFiltered = $categorySlug !== null || $search !== '';

        $featured = (! $isFiltered && $request->integer('page', 1) <= 1)
            ? $this->featuredNewsQuery()->first()
            : null;

        $news = News::query()
            ->published()
            ->with(['category', 'author'])
            ->when($categorySlug, fn (Builder $query, string $slug) => $query->whereHas(
                'category',
                fn (Builder $categoryQuery) => $categoryQuery->where('slug', $slug),
            ))
            ->when($search !== '', function (Builder $query) use ($search) {
                // Escape LIKE wildcards so user input is matched literally.
                $term = '%'.addcslashes($search, '\\%_').'%';
                $query->where(fn (Builder $inner) => $inner
                    ->where('title', 'like', $term)
                    ->orWhere('lead_paragraph', 'like', $term));
            })
            ->when($featured, fn (Builder $query) => $query->whereKeyNot($featured->getKey()))
            ->latestPublished()
            ->paginate(self::NEWS_PER_PAGE)
            ->withQueryString();

        return view('berita', [
            'featured' => $featured,
            'news' => $news,
            'categories' => NewsCategory::query()->orderBy('id')->get(),
            'activeCategory' => $categorySlug,
            'search' => $search,
        ]);
    }

    public function showNews(string $slug): View
    {
        $article = News::query()
            ->published()
            ->with(['category', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = $article->getRelatedNews(5);

        return view('berita-detail', [
            'article' => $article,
            'relatedPicks' => $related->take(2),
            'moreNews' => $related->slice(2, 3),
            'total' => News::query()->published()->count(),
        ]);
    }

    public function allGalleries(): View
    {
        return view('galeri', [
            'galleries' => Gallery::query()
                ->orderByDesc('created_at')
                ->orderBy('id')
                ->paginate(self::GALLERY_PER_PAGE),
        ]);
    }

    public function redirectWhatsAppRegistration(WhatsAppRegistrationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Defence in depth: the service re-checks the payload before building the outbound link.
        if (! $this->whatsAppService->validateInput($data)) {
            return redirect()->to(route('home').'#daftar')->withInput();
        }

        return redirect()->away($this->whatsAppService->generateDeepLinkUrl($data));
    }

    /**
     * @return Builder<News>
     */
    private function featuredNewsQuery(): Builder
    {
        return News::query()
            ->published()
            ->with(['category', 'author'])
            ->orderByDesc('is_featured')
            ->latestPublished();
    }

    /**
     * Groups schedules into the homepage tabs, keeping the fixed weekday order.
     *
     * @return Collection<string, array{key: string, label: string, subtitle: string, items: Collection<int, TrainingSchedule>}>
     */
    private function schedulesByDay(): Collection
    {
        $schedules = TrainingSchedule::query()
            ->leftJoin('age_programs', 'age_programs.id', '=', 'training_schedules.age_program_id')
            ->select('training_schedules.*')
            ->orderByRaw('age_programs.sort_order IS NULL')
            ->orderBy('age_programs.sort_order')
            ->orderBy('training_schedules.id')
            ->get()
            ->groupBy('day_of_week');

        return collect(TrainingSchedule::DAYS)
            ->map(fn (array $day, string $dayName) => $day + [
                'items' => $schedules->get($dayName, collect())->values(),
            ])
            ->filter(fn (array $day) => $day['items']->isNotEmpty());
    }
}

