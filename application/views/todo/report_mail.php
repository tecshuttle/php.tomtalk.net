<?php
function add_blank($job_desc) {
    $lines = explode("</br>", $job_desc);

    foreach($lines as &$line) {
        $line = '        ' . $line;
    }

    return implode("\n", $lines);
}
?>

<pre>
<?php foreach ($days as $day): ?>

<?= $day['date'] ?>

<?php if (count($day['jobs']) === 0): ?>
    无
<?php else: ?>
<?php foreach ($day['jobs'] as $job): ?>
    <?= $job->job_name ?>  <?= ($job->status === '1' ? 'done' : 'planing') ?>  <?= ($job->time_long / 3600) ?>H<?= (empty($job->job_desc) ? '' : "\n" . add_blank($job->job_desc)) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
<?php endforeach; ?>


EOF
</pre>