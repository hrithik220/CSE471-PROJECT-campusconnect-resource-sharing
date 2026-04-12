<?php $__env->startSection('title', $resource->title); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-8">

    <a href="<?php echo e(route('map.index')); ?>" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-blue-600 mb-6">
        ← Back to Map
    </a>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        
        <?php if($resource->image_paths && count($resource->image_paths) > 0): ?>
        <div class="flex gap-3 overflow-x-auto p-4 bg-gray-50 border-b">
            <?php $__currentLoopData = $resource->image_paths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <img src="<?php echo e(Storage::url($img)); ?>" class="h-56 w-auto rounded-xl object-cover flex-shrink-0 shadow-sm">
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center text-7xl border-b">
            <?php echo e($resource->category->icon); ?>

        </div>
        <?php endif; ?>

        <div class="p-6 md:p-8">

            
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                        <?php echo e($resource->category->icon); ?> <?php echo e($resource->category->name); ?>

                    </span>
                    <h1 class="text-2xl font-bold text-gray-800 mt-2"><?php echo e($resource->title); ?></h1>
                </div>
                <?php if(auth()->id() === $resource->user_id): ?>
                <a href="<?php echo e(route('resources.edit', $resource)); ?>"
                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-xl text-sm font-medium flex-shrink-0">
                    ✏️ Edit
                </a>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-wrap gap-2 mt-4">
                <span class="text-xs px-3 py-1 rounded-full font-medium
                    <?php echo e($resource->condition==='new' ? 'bg-green-100 text-green-700' :
                       ($resource->condition==='good' ? 'bg-blue-100 text-blue-700' :
                       ($resource->condition==='fair' ? 'bg-yellow-100 text-yellow-700' :
                       'bg-red-100 text-red-600'))); ?>">
                    Condition: <?php echo e(ucfirst($resource->condition)); ?>

                </span>
                <span class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full font-medium">
                    <?php echo e($resource->sharing_type==='free' ? '🆓 Free Lending' : '🔄 Exchange'); ?>

                </span>
                <span class="text-xs px-3 py-1 rounded-full font-medium
                    <?php echo e($resource->availability_status==='available' ? 'bg-emerald-100 text-emerald-700' :
                       ($resource->availability_status==='borrowed' ? 'bg-orange-100 text-orange-700' :
                       'bg-red-100 text-red-600')); ?>">
                    <?php echo e($resource->availability_status==='available' ? '✅ Available' :
                       ($resource->availability_status==='borrowed' ? '🔄 Borrowed' : '❌ Unavailable')); ?>

                </span>
                <?php if($resource->availability_until): ?>
                <span class="text-xs bg-orange-100 text-orange-700 px-3 py-1 rounded-full">
                    📅 Until <?php echo e(\Carbon\Carbon::parse($resource->availability_until)->format('d M Y')); ?>

                </span>
                <?php endif; ?>
            </div>

            
            <div class="mt-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Description</p>
                <p class="text-gray-700 text-sm leading-relaxed"><?php echo e($resource->description); ?></p>
            </div>

            
            <?php if($resource->pickup_address): ?>
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-blue-700 uppercase tracking-widest mb-1">📍 Pickup Location</p>
                <p class="text-sm text-blue-800 font-medium"><?php echo e($resource->pickup_address); ?></p>
                <a href="<?php echo e(route('map.index')); ?>" class="text-xs text-blue-600 hover:underline mt-1 inline-block">
                    View on Map →
                </a>
            </div>
            <?php endif; ?>

            <hr class="my-6 border-gray-100">

            
            <div class="bg-gray-50 rounded-2xl p-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">👤 Owner Profile</p>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600
                                flex items-center justify-center text-xl font-bold text-white flex-shrink-0">
                        <?php echo e(strtoupper(substr($resource->owner->name, 0, 1))); ?>

                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800"><?php echo e($resource->owner->name); ?></p>
                        <p class="text-xs text-gray-500">Member since <?php echo e($resource->owner->created_at->format('M Y')); ?></p>
                    </div>
                    <div class="text-center bg-white rounded-xl px-5 py-3 shadow-sm border">
                        <p class="text-2xl font-bold text-yellow-500"><?php echo e(number_format($resource->owner->credibility_score,1)); ?></p>
                        <p class="text-xs text-gray-400">Credibility</p>
                        <div class="flex justify-center mt-1">
                            <?php for($i=1;$i<=5;$i++): ?>
                                <span class="text-xs <?php echo e($i<=round($resource->owner->credibility_score) ? 'text-yellow-400':'text-gray-300'); ?>">★</span>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 mt-4">
                    <div class="bg-white rounded-xl p-3 text-center border">
                        <p class="text-xl font-bold text-blue-600"><?php echo e($resource->owner->resources()->where('is_approved',true)->count()); ?></p>
                        <p class="text-xs text-gray-400">Items Shared</p>
                    </div>
                    <div class="bg-white rounded-xl p-3 text-center border">
                        <p class="text-xl font-bold text-green-600"><?php echo e($resource->owner->karma_points); ?></p>
                        <p class="text-xs text-gray-400">Karma Points</p>
                    </div>
                    <div class="bg-white rounded-xl p-3 text-center border">
                        <p class="text-xl font-bold text-purple-600"><?php echo e($resource->view_count); ?></p>
                        <p class="text-xs text-gray-400">Views</p>
                    </div>
                </div>
            </div>

            
            <div class="mt-8">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">💬 Borrower Reviews</p>
                    <div class="flex items-center gap-1 text-sm">
                        <span class="text-yellow-400">★</span>
                        <span class="font-semibold"><?php echo e(number_format($resource->averageRating(),1)); ?></span>
                        <span class="text-gray-400">(<?php echo e($resource->reviews->count()); ?>)</span>
                    </div>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $resource->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border border-gray-100 rounded-xl p-4 mb-3 bg-gray-50">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-indigo-200 flex items-center justify-center text-xs font-bold text-indigo-700">
                                <?php echo e(strtoupper(substr($review->reviewer->name,0,1))); ?>

                            </div>
                            <span class="text-sm font-medium"><?php echo e($review->reviewer->name); ?></span>
                        </div>
                        <div class="flex">
                            <?php for($i=1;$i<=5;$i++): ?>
                                <span class="text-sm <?php echo e($i<=$review->rating ? 'text-yellow-400':'text-gray-300'); ?>">★</span>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <?php if($review->comment): ?>
                        <p class="text-sm text-gray-600"><?php echo e($review->comment); ?></p>
                    <?php endif; ?>
                    <p class="text-xs text-gray-400 mt-1"><?php echo e($review->created_at->diffForHumans()); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8 bg-gray-50 rounded-xl text-gray-400">
                    <p class="text-3xl mb-2">💭</p>
                    <p class="text-sm">No reviews yet</p>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="mt-6">
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->id() !== $resource->user_id && $resource->availability_status==='available'): ?>
                        <a href="#" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl">
                            📩 Send Borrow Request
                        </a>
                    <?php elseif(auth()->id() === $resource->user_id): ?>
                        <div class="w-full text-center bg-gray-100 text-gray-500 font-medium py-3 rounded-xl text-sm">This is your resource</div>
                    <?php else: ?>
                        <div class="w-full text-center bg-gray-100 text-gray-400 font-medium py-3 rounded-xl text-sm">Currently not available</div>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl">
                        Login to Send Borrow Request
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hrithik\resources\views/resources/show.blade.php ENDPATH**/ ?>