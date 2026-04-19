<?php $__env->startSection('title', 'My Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">🔔 Notifications</h1>
        <p class="text-gray-500 text-sm mt-1">Due-date reminders and community alerts</p>
    </div>
    <?php if($notifications->total() > 0): ?>
        <form method="POST" action="<?php echo e(route('reminders.read-all')); ?>">
            <?php echo csrf_field(); ?>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
                ✓ Mark all as read
            </button>
        </form>
    <?php endif; ?>
</div>

<?php if($notifications->isEmpty()): ?>
    <div class="bg-white rounded-2xl shadow p-16 text-center">
        <div class="text-6xl mb-4">🎉</div>
        <p class="text-gray-500 text-lg">You're all caught up! No notifications.</p>
    </div>
<?php else: ?>
    <div class="space-y-3">
        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $data = $notif->data; ?>
            <div class="bg-white rounded-xl shadow-sm border <?php echo e($notif->read_at ? 'border-gray-100' : 'border-indigo-300 bg-indigo-50'); ?> p-4 flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="text-2xl mt-0.5">
                        <?php if($data['type'] === 'due_date_reminder'): ?> ⏰
                        <?php elseif($data['type'] === 'badge_awarded'): ?> 🏆
                        <?php elseif($data['type'] === 'report_status'): ?> 📋
                        <?php else: ?> 🔔
                        <?php endif; ?>
                    </span>
                    <div>
                        <p class="font-medium text-gray-800"><?php echo e($data['message'] ?? 'You have a new notification.'); ?></p>
                        <?php if(isset($data['due_date'])): ?>
                            <p class="text-sm text-gray-500 mt-0.5">Due: <?php echo e(\Carbon\Carbon::parse($data['due_date'])->format('d M Y')); ?></p>
                        <?php endif; ?>
                        <p class="text-xs text-gray-400 mt-1"><?php echo e($notif->created_at->diffForHumans()); ?></p>
                    </div>
                </div>
                <?php if(!$notif->read_at): ?>
                    <form method="POST" action="<?php echo e(route('reminders.read', $notif->id)); ?>" class="shrink-0">
                        <?php echo csrf_field(); ?>
                        <button class="text-xs text-indigo-600 hover:underline whitespace-nowrap">Mark read</button>
                    </form>
                <?php else: ?>
                    <span class="text-xs text-gray-400 shrink-0">Read</span>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-6"><?php echo e($notifications->links()); ?></div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campusshare_final\resources\views/reminders/notifications.blade.php ENDPATH**/ ?>