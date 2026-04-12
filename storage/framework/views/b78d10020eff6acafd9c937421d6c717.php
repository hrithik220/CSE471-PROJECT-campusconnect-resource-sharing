<?php $__env->startSection('title', 'Pickup Map'); ?>
<?php $__env->startSection('content'); ?>

<?php
    $mapResources = $resources->map(function($r) {
        return [
            'title'   => $r->title,
            'lat'     => (float)$r->pickup_lat,
            'lng'     => (float)$r->pickup_lng,
            'address' => $r->pickup_address,
            'url'     => route('resources.show', $r->id),
        ];
    });
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📍 Pickup Locator</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow p-4">
            <h2 class="font-semibold text-gray-700 mb-4">Available Resources</h2>
            <?php $__empty_1 = true; $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border-b py-3 cursor-pointer hover:bg-blue-50 rounded p-2"
                 onclick="focusMap(<?php echo e($resource->pickup_lat); ?>, <?php echo e($resource->pickup_lng); ?>, '<?php echo e($resource->title); ?>')">
                <p class="font-semibold text-sm text-gray-800"><?php echo e($resource->title); ?></p>
                <p class="text-xs text-gray-500"><?php echo e($resource->category->name); ?></p>
                <p class="text-xs text-blue-600 mt-1">📍 <?php echo e($resource->pickup_address); ?></p>
                <a href="<?php echo e(route('resources.show', $resource)); ?>" class="text-xs text-blue-500 hover:underline">View →</a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-gray-400 text-sm">No resources found.</p>
            <?php endif; ?>
        </div>

        <div class="md:col-span-2 rounded-2xl shadow overflow-hidden" style="height:500px">
            <div id="map" style="width:100%; height:100%;"></div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    var map = L.map('map').setView([23.7809, 90.4012], 17);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'OpenStreetMap'
    }).addTo(map);

    var resources = <?php echo json_encode($mapResources, 15, 512) ?>;

    resources.forEach(function(r) {
        L.marker([r.lat, r.lng])
            .addTo(map)
            .bindPopup('<b>' + r.title + '</b><br>' + r.address + '<br><a href="' + r.url + '">View →</a>');
    });

    function focusMap(lat, lng, title) {
        map.setView([lat, lng], 18);
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hrithik\resources\views/map/index.blade.php ENDPATH**/ ?>