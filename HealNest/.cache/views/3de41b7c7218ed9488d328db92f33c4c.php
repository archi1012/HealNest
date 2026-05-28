<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label', 'value', 'icon' => '📊', 'color' => 'forest', 'sub' => null]));

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

foreach (array_filter((['label', 'value', 'icon' => '📊', 'color' => 'forest', 'sub' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="bg-white rounded-xl p-5 shadow-sm border border-tan/20 flex items-start gap-4">
    <div class="text-3xl"><?php echo e($icon); ?></div>
    <div>
        <p class="text-gray-500 text-xs uppercase tracking-wide"><?php echo e($label); ?></p>
        <p class="text-2xl font-bold text-forest font-heading"><?php echo e($value); ?></p>
        <?php if($sub): ?><p class="text-xs text-earthbrown mt-0.5"><?php echo e($sub); ?></p><?php endif; ?>
    </div>
</div>
<?php /**PATH /Users/Adit/Documents/GitHub/HealNest/HealNest/resources/views/components/stat-card.blade.php ENDPATH**/ ?>