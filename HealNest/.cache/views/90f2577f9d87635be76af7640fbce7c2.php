<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['mood' => 3]));

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

foreach (array_filter((['mood' => 3]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $labels = [1 => 'Very Low', 2 => 'Low', 3 => 'Neutral', 4 => 'Good', 5 => 'Great'];
    $colors = [1 => 'bg-red-400', 2 => 'bg-orange-400', 3 => 'bg-yellow-400', 4 => 'bg-lightgreen', 5 => 'bg-forest'];
    $emojis = [1 => '😢', 2 => '😕', 3 => '😐', 4 => '🙂', 5 => '😄'];
    $pct = ($mood / 5) * 100;
?>
<div class="flex items-center gap-3">
    <span class="text-2xl"><?php echo e($emojis[$mood] ?? '😐'); ?></span>
    <div class="flex-1">
        <div class="flex justify-between text-xs text-gray-500 mb-1">
            <span><?php echo e($labels[$mood] ?? 'Neutral'); ?></span>
            <span><?php echo e($mood); ?>/5</span>
        </div>
        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
            <div class="<?php echo e($colors[$mood] ?? 'bg-yellow-400'); ?> h-full rounded-full transition-all duration-500"
                 style="width: <?php echo e($pct); ?>%"></div>
        </div>
    </div>
</div>
<?php /**PATH /Users/Adit/Documents/GitHub/HealNest/HealNest/resources/views/components/mood-bar.blade.php ENDPATH**/ ?>