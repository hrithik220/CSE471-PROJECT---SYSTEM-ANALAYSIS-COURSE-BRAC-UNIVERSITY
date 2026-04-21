<?php $__env->startSection('title', 'Admin: Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">⚙ Admin — Report Management</h1>
    <p class="text-gray-500 text-sm mt-1">Review, investigate and resolve community reports</p>
</div>


<div class="grid grid-cols-4 gap-4 mb-6">
    <?php $__currentLoopData = ['pending'=>['🟡','Pending','yellow'],'under_review'=>['🔵','Under Review','blue'],'resolved'=>['🟢','Resolved','green'],'dismissed'=>['⚫','Dismissed','gray']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$icon,$label,$color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-<?php echo e($color); ?>-400 text-center">
            <div class="text-2xl"><?php echo e($icon); ?></div>
            <div class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($stats[$key]); ?></div>
            <div class="text-xs text-gray-400"><?php echo e($label); ?></div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<form method="GET" class="flex gap-3 mb-5">
    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <option value="">All Statuses</option>
        <?php $__currentLoopData = ['pending','under_review','resolved','dismissed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst(str_replace('_',' ',$s))); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <option value="">All Types</option>
        <?php $__currentLoopData = ['lost','damaged','unreturned']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($t); ?>" <?php echo e(request('type') === $t ? 'selected' : ''); ?>><?php echo e(ucfirst($t)); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">Filter</button>
    <a href="<?php echo e(route('admin.reports.index')); ?>" class="text-sm text-gray-500 hover:text-gray-700 flex items-center">Clear</a>
</form>


<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">#</th>
                <th class="px-4 py-3 text-left">Resource</th>
                <th class="px-4 py-3 text-left">Reporter</th>
                <th class="px-4 py-3 text-left">Borrower</th>
                <th class="px-4 py-3 text-left">Type</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Filed</th>
                <th class="px-4 py-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $sc = ['pending'=>'bg-yellow-100 text-yellow-800','under_review'=>'bg-blue-100 text-blue-800','resolved'=>'bg-green-100 text-green-800','dismissed'=>'bg-gray-100 text-gray-500'];
                    $ti = ['lost'=>'🔍','damaged'=>'💥','unreturned'=>'⏳'];
                ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-400">#<?php echo e($report->id); ?></td>
                    <td class="px-4 py-3 font-medium text-gray-800"><?php echo e(Str::limit($report->resource->title, 25)); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($report->reporter->name); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($report->borrower->name); ?></td>
                    <td class="px-4 py-3"><?php echo e($ti[$report->report_type]); ?> <?php echo e(ucfirst($report->report_type)); ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium <?php echo e($sc[$report->status]); ?>">
                            <?php echo e(ucfirst(str_replace('_',' ',$report->status))); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs"><?php echo e($report->created_at->format('d M Y')); ?></td>
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('admin.reports.show', $report)); ?>"
                           class="text-indigo-600 hover:underline text-xs font-medium">Review →</a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-gray-400">No reports found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="mt-4"><?php echo e($reports->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campusshare_final\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>