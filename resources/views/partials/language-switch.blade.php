<form class="language-switch" method="POST" action="{{ route('language.update') }}">
    @csrf
    <input type="hidden" name="locale" value="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}">
    <button type="submit" class="theme-toggle" lang="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}" aria-label="{{ app()->getLocale() === 'ar' ? 'Switch to English' : 'التبديل إلى العربية' }}">
        {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
    </button>
</form>
