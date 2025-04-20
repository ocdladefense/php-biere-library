<?php


function widget($widgetId) {
    $route = getRoute();


    ob_start();

    print $route == "home" ? homepage_banner_widget() : "";

    $out = ob_get_contents();
    ob_end_clean();
    return $out;
}



function homepage_banner_widget() {
$out = <<<"EOT"
<div class="header-content">
    <h1>
        <span class="bl-title">The</span>
        <span class="bl-title">Bière</span>
        <span class="bl-title">Library</span>
    </h1>
    <p class="darken-background">Belgian comfort food<br />Downtown Corvallis, Oregon</p>
    <br />
    <h2 class="darken-background">Lunch, Dinner, Events, Tap Beers, Cocktails</h2>
    <br />

    <?php if(config("online_ordering_enabled",false)): ?>
    <!-- <button>Order Online</button> -->
    <?php endif; ?>        
</div>
<div class="home-nav">
    <a class="bl-button" href="/food">Food</a>
    <a class="bl-button" href="/drink">Drinks</a>
    <a class="bl-button" href="/events">Events</a>
    <a class="bl-button" href="/about">About</a>
</div>
<div class="desktop all-ages-callout">
    All ages welcome!
</div>
EOT;

return $out;
}