<?php
    $now = $carbon::now()->toDateTime();
    $rusak = $machineOnRepair->tgl_kerusakan;
    $start = $carbon::parse($rusak)->toDateTime();

    $interval =  $start->diff($now)->format("%a Hari %H Jam %I Menit %S Detik");
    $tmpDays =  $start->diff($now)->format("%a");
    $tmpHours =  $start->diff($now)->format("%H");
    $tmpMinutes =  $start->diff($now)->format("%I");
    $tmpSeconds =  $start->diff($now)->format("%S");

    $days = (int)$tmpDays;
    $hours = (int)$tmpHours;
    $minutes = (int)$tmpMinutes;
    $seconds = (int)$tmpSeconds;
?>
<script>
    const seconds = <?php echo json_encode($seconds) ?>
    const minutes = <?php echo json_encode($minutes) ?>
    const hours = <?php echo json_encode($hours) ?>
    const days = <?php echo json_encode($days) ?>
    
    const timerRef = document.querySelector(".downtime" + <?php echo $machineOnRepair ?>);
    console.log(timerRef);
</script>