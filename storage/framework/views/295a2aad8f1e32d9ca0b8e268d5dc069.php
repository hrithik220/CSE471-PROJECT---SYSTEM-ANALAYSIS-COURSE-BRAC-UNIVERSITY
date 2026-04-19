<?php $__env->startSection('title','Transactions'); ?>
<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">My Transactions</h1>
    <p class="text-slate-500 text-sm mt-1">Manage your borrowing and lending activity</p>
</div>


<div x-data="{ tab: 'borrowing' }" x-init="
    const params = new URLSearchParams(window.location.search);
    if (params.get('tab')) tab = params.get('tab');
">
    <div class="flex gap-2 mb-6 bg-white border border-slate-200 rounded-2xl p-1.5 w-fit">
        <button @click="tab='borrowing'" :class="tab==='borrowing' ? 'bg-blue-600 text-white shadow' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-xl text-sm font-semibold transition-all">
            📦 Borrowing (<?php echo e($borrowing->count()); ?>)
        </button>
        <button @click="tab='lending'" :class="tab==='lending' ? 'bg-blue-600 text-white shadow' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-xl text-sm font-semibold transition-all">
            🤝 Lending (<?php echo e($lending->count()); ?>)
        </button>
        <button @click="tab='requests'" :class="tab==='requests' ? 'bg-blue-600 text-white shadow' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-xl text-sm font-semibold transition-all relative">
            🔔 Requests
            <?php if($pendingRequests->count()): ?>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center"><?php echo e($pendingRequests->count()); ?></span>
            <?php endif; ?>
        </button>
        <button @click="tab='history'" :class="tab==='history' ? 'bg-blue-600 text-white shadow' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-xl text-sm font-semibold transition-all">
            🗂 History
        </button>
    </div>

    
    <div x-show="tab==='borrowing'">
        <?php if($borrowing->count()): ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $borrowing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $overdue = $tx->isOverdue(); ?>
            <div class="bg-white rounded-2xl border <?php echo e($overdue ? 'border-red-300' : 'border-slate-200'); ?> p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <?php if($tx->resource->photo): ?>
                    <img src="<?php echo e(asset('storage/'.$tx->resource->photo)); ?>" class="w-12 h-12 object-cover rounded-xl">
                    <?php else: ?>
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                    <?php endif; ?>
                </div>
                <div class="flex-1 min-w-0">
                    <a href="<?php echo e(route('resources.show', $tx->resource)); ?>" class="font-semibold text-slate-800 hover:text-blue-600"><?php echo e($tx->resource->title); ?></a>
                    <p class="text-sm text-slate-500">from <?php echo e($tx->lender->name); ?></p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold status-<?php echo e($overdue ? 'overdue' : $tx->status); ?>">
                            <?php echo e($overdue ? '🔴 OVERDUE' : ucfirst($tx->status)); ?>

                        </span>
                        <span class="text-xs text-slate-400">Due: <span class="<?php echo e($overdue ? 'text-red-600 font-bold' : 'text-slate-600 font-medium'); ?>"><?php echo e($tx->due_date->format('M d, Y')); ?></span></span>
                    </div>
                </div>
                <?php if($overdue): ?>
                <div class="text-red-500 text-xs font-bold"><?php echo e($tx->due_date->diffForHumans()); ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <p class="text-slate-400">You're not borrowing anything right now.</p>
            <a href="<?php echo e(route('resources.index')); ?>" class="inline-block mt-2 text-blue-600 font-semibold hover:underline text-sm">Browse resources →</a>
        </div>
        <?php endif; ?>
    </div>

    
    <div x-show="tab==='lending'">
        <?php if($lending->count()): ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $lending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $overdue = $tx->isOverdue(); ?>
            <div class="bg-white rounded-2xl border <?php echo e($overdue ? 'border-red-300' : 'border-slate-200'); ?> p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <?php if($tx->resource->photo): ?>
                    <img src="<?php echo e(asset('storage/'.$tx->resource->photo)); ?>" class="w-12 h-12 object-cover rounded-xl">
                    <?php else: ?>
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                    <?php endif; ?>
                </div>
                <div class="flex-1 min-w-0">
                    <a href="<?php echo e(route('resources.show', $tx->resource)); ?>" class="font-semibold text-slate-800 hover:text-blue-600"><?php echo e($tx->resource->title); ?></a>
                    <p class="text-sm text-slate-500">to <?php echo e($tx->borrower->name); ?></p>
                    <?php if($tx->message): ?><p class="text-xs text-slate-400 italic mt-0.5">"<?php echo e($tx->message); ?>"</p><?php endif; ?>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold status-<?php echo e($overdue ? 'overdue' : $tx->status); ?>">
                            <?php echo e($overdue ? '🔴 OVERDUE' : ucfirst($tx->status)); ?>

                        </span>
                        <span class="text-xs text-slate-400">Due: <?php echo e($tx->due_date->format('M d, Y')); ?></span>
                    </div>
                </div>
                <?php if(in_array($tx->status, ['active','overdue'])): ?>
                <form method="POST" action="<?php echo e(route('transactions.return', $tx)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-2 rounded-xl transition-colors">Mark Returned</button>
                </form>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <p class="text-slate-400">You're not lending anything right now.</p>
        </div>
        <?php endif; ?>
    </div>

    
    <div x-show="tab==='requests'">
        <?php if($pendingRequests->count()): ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl border border-yellow-200 bg-yellow-50/30 p-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center font-bold text-yellow-700 flex-shrink-0 text-sm">
                        <?php echo e(strtoupper(substr($tx->borrower->name, 0, 2))); ?>

                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-slate-800"><?php echo e($tx->borrower->name); ?> <span class="font-normal text-slate-500">wants to borrow</span> <?php echo e($tx->resource->title); ?></p>
                        <p class="text-sm text-slate-500 mt-0.5">Return by: <strong><?php echo e($tx->due_date->format('M d, Y')); ?></strong></p>
                        <?php if($tx->exchange_item): ?><p class="text-sm text-slate-500">Offering: <strong><?php echo e($tx->exchange_item); ?></strong></p><?php endif; ?>
                        <?php if($tx->message): ?><p class="text-sm text-slate-500 italic mt-1">"<?php echo e($tx->message); ?>"</p><?php endif; ?>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <form method="POST" action="<?php echo e(route('transactions.approve', $tx)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">✓ Approve</button>
                        </form>
                        <form method="POST" action="<?php echo e(route('transactions.reject', $tx)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold px-4 py-2 rounded-xl transition-colors">✗ Reject</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <p class="text-slate-400">No pending requests.</p>
        </div>
        <?php endif; ?>
    </div>

    
    <div x-show="tab==='history'">
        <?php if($history->count()): ?>
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600">Resource</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600">Role</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600">With</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600">Status</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-800"><?php echo e($tx->resource->title); ?></td>
                        <td class="px-5 py-3 text-slate-500"><?php echo e($tx->borrower_id === auth()->id() ? 'Borrowed' : 'Lent'); ?></td>
                        <td class="px-5 py-3 text-slate-500"><?php echo e($tx->borrower_id === auth()->id() ? $tx->lender->name : $tx->borrower->name); ?></td>
                        <td class="px-5 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-semibold status-<?php echo e($tx->status); ?>"><?php echo e(ucfirst($tx->status)); ?></span></td>
                        <td class="px-5 py-3 text-slate-400"><?php echo e($tx->updated_at->format('M d, Y')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <p class="text-slate-400">No transaction history yet.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campusshare_final\resources\views/transactions/index.blade.php ENDPATH**/ ?>