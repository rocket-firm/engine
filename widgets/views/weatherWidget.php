<?php foreach ($cities as $cityName => $cityId) { ?>
    <?php $weather = $forecast[$cityId]; ?>
    <div class="city clearfix" id="forecast-<?php echo $cityId; ?>">
        <img src="/images/weather-icons/<?php echo $weather['code']; ?>.png"
             alt="<?php echo $cityName; ?> <?php echo ($weather['temp'] > 0) ? '+ ' . $weather['temp'] : $weather['temp']; ?>&deg;C">
        <span>
            <b><?php echo $cityName; ?> <?php echo ($weather['temp'] > 0) ? '+ ' . $weather['temp'] : $weather['temp']; ?>&deg;C</b>
            <span><?php echo $weather['text']; ?></span>
        </span>
    </div>
<?php } ?>
<ul class="cities">
    <?php foreach ($cities as $cityName => $cityId) { ?>
        <li data-id="<?= $cityId ?>"><?= $cityName ?></li>
    <?php } ?>
</ul>
