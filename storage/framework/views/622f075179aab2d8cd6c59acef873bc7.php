<?php $__env->startSection('title', 'My Statistics'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">📊 My Contribution Statistics</h1>
    <p class="text-gray-500 text-sm mt-1">Track your community impact and karma</p>
</div>


<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow p-5 text-center border-t-4 border-indigo-500">
        <div class="text-4xl font-bold text-indigo-600"><?php echo e($stat->total_items_lent); ?></div>
        <div class="text-sm text-gray-500 mt-1 font-medium">Items Lent</div>
        <div class="text-xl mt-1">📤</div>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 text-center border-t-4 border-green-500">
        <div class="text-4xl font-bold text-green-600"><?php echo e($stat->total_items_borrowed); ?></div>
        <div class="text-sm text-gray-500 mt-1 font-medium">Items Borrowed</div>
        <div class="text-xl mt-1">📥</div>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 text-center border-t-4 border-yellow-500">
        <div class="text-4xl font-bold text-yellow-600"><?php echo e(number_format($stat->karma_points)); ?></div>
        <div class="text-sm text-gray-500 mt-1 font-medium">Karma Points</div>
        <div class="text-xl mt-1">⭐</div>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 text-center border-t-4 border-emerald-500">
        <div class="text-4xl font-bold text-emerald-600"><?php echo e($stat->items_saved_from_purchase); ?></div>
        <div class="text-sm text-gray-500 mt-1 font-medium">Purchases Saved</div>
        <div class="text-xl mt-1">🌍</div>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

    
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">🌱 Environmental Impact</h2>
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <div class="flex justify-between text-sm text-gray-500 mb-1">
                    <span>Impact Score</span>
                    <span><?php echo e(number_format($stat->environmental_impact_score, 1)); ?> pts</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <?php $pct = min(100, ($stat->environmental_impact_score / 100) * 100) ?>
                    <div class="bg-emerald-500 h-3 rounded-full transition-all" style="width: <?php echo e($pct); ?>%"></div>
                </div>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3">
            By lending items, you've helped the community avoid <?php echo e($stat->items_saved_from_purchase); ?> new purchases,
            reducing waste and carbon footprint. 🌍
        </p>
    </div>

    
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">🎖 Badges Earned</h2>
        <?php if($user->badges->isEmpty()): ?>
            <p class="text-gray-400 text-sm">No badges yet. Start lending to earn your first badge!</p>
        <?php else: ?>
            <div class="flex flex-wrap gap-2">
                <?php $__currentLoopData = $user->badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="inline-flex items-center gap-1 text-sm font-medium px-3 py-1.5 rounded-full text-white"
                          style="background-color: <?php echo e($badge->color); ?>">
                        <?php echo e($badge->icon); ?> <?php echo e($badge->name); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
        <a href="<?php echo e(route('leaderboard.badges')); ?>" class="text-indigo-600 text-xs hover:underline mt-3 inline-block">
            View all badges →
        </a>
    </div>
</div>


<div class="bg-white rounded-2xl shadow p-6">
    <h2 class="font-semibold text-gray-700 mb-4">📈 Monthly Lending (Last 6 Months)</h2>
    <?php if($monthlyLending->isEmpty()): ?>
        <p class="text-gray-400 text-sm">No lending history yet.</p>
    <?php else: ?>
        <div class="flex items-end gap-3 h-32">
            <?php $maxCount = $monthlyLending->max('count') ?: 1; ?>
            <?php $__currentLoopData = $monthlyLending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $height = max(10, ($row->count / $maxCount) * 100); ?>
                <div class="flex flex-col items-center flex-1 gap-1">
                    <span class="text-xs font-bold text-indigo-700"><?php echo e($row->count); ?></span>
                    <div class="w-full bg-indigo-500 rounded-t-md transition-all"
                         style="height: <?php echo e($height); ?>%"></div>
                    <span class="text-xs text-gray-400"><?php echo e(date('M', mktime(0,0,0,$row->month,1))); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campusshare_final\resources\views/statistics/index.blade.php ENDPATH**/ ?>