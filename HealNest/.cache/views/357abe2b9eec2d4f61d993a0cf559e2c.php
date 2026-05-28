<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'success', 'message']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['type' => 'success', 'message']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $styles = [
        'success' => 'bg-lightgreen/20 border-lightgreen text-forest',
        'error'   => 'bg-red-50 border-red-300 text-red-700',
        'warning' => 'bg-tan/20 border-tan text-earthbrown',
    ];
    $icons = ['success' => '✅', 'error' => '❌', 'warning' => '⚠️'];
?>
<div x-data="{ show: true }" x-show="show" x-transition
     class="flex items-center justify-between p-3 mb-4 rounded-lg border text-sm <?php echo e($styles[$type] ?? $styles['success']); ?>">
    <span><?php echo e($icons[$type] ?? '✅'); ?> <?php echo e($message); ?></span>
    <button @click="show = false" class="ml-4 opacity-60 hover:opacity-100">✕</button>
</div>
<?php /**PATH /Users/Adit/Documents/GitHub/HealNest/HealNest/resources/views/components/alert-banner.blade.php ENDPATH**/ ?>