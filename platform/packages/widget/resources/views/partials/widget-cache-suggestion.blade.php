<div id="widget-cache-suggestion" class="alert alert-info alert-dismissible" style="margin: 20px 0;">
    <div class="d-flex">
        <div class="me-3">
            <x-core::icon name="ti ti-bulb" class="text-info" style="font-size: 24px;" />
        </div>
        <div class="flex-fill">
            <h5 class="mb-1">{{ trans('packages/widget::widget.cache_suggestion.title') }}</h5>
            <p class="mb-1">
                {{ trans('packages/widget::widget.cache_suggestion.description') }}
                {{ trans('packages/widget::widget.cache_suggestion.benefits') }}
            </p>
            <div class="mt-2">
                <a href="{{ route('settings.cache') }}#widget-cache-settings" class="btn btn-info btn-sm">
                    <x-core::icon name="ti ti-settings" class="me-1" />
                    {{ trans('packages/widget::widget.cache_suggestion.enable_button') }}
                </a>
                <button type="button" class="btn btn-outline-secondary btn-sm ms-2 dismiss-widget-suggestion">
                    <x-core::icon name="ti ti-eye-off" class="me-1" />
                    {{ trans('packages/widget::widget.cache_suggestion.dismiss_button') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dismissBtn = document.querySelector('.dismiss-widget-suggestion');
        if (dismissBtn) {
            dismissBtn.addEventListener('click', function() {
                // Set a cookie to dismiss the suggestion for a week
                const expiryDate = new Date();
                expiryDate.setDate(expiryDate.getDate() + 7);
                const secure = window.location.protocol === 'https:' ? '; Secure' : '';
                document.cookie = 'widget_cache_suggestion_dismissed=1; expires=' + expiryDate.toUTCString() + '; path=/; SameSite=Lax' + secure;

                // Hide the suggestion
                document.getElementById('widget-cache-suggestion').style.display = 'none';
            });
        }
    });
</script>
