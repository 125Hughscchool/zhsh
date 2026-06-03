<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ OPcache сброшен";
}
if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
    echo " + APCu сброшен";
}
?>