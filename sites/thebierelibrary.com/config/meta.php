<?php
/**
 * This file contains metadata for paths access through this installation.
 */

$host = $_GET["host"] ?? $_SERVER["HTTP_HOST"];

$meta = array(
    "home" => array(
        "title" => "Belgian Comfort Food, Beer & Cocktails at The Bière Library",
        "meta" => array(
            array("property" => "og:title", "content" => "Belgian Comfort Food, Beer & Cocktails"),
            array("property" => "og:image", "content" => "https://$host/themes/biere-library/assets/images/apple-preview/home.png")
        )
    ),
    "event/2024-04-29/a-meeting-of-libraries-belgian-french-music-experience" => array(
        "title" => "A Meeting of Libraries: Belgian & French Music Experience",
        "meta" => array(
            array("property" => "og:title", "content" => "A Meeting of Libraries: Belgian & French Music Experience"),
            array("property" => "og:image", "content" => "https://$host/themes/biere-library/assets/images/apple-preview/meeting-of-libraries.png")
        )
    ),
    "food" => array(
        "title" => "Lunch & Dinner at The Bière Library"  
    ),
    "drink" => array(
        "title" => "Beer, Cocktails, Wine & Spirits at The Bière Library"
    ),
    "events" => array(
        "title" => "Weekly & Special Events at The Bière Library"
    ),
    "about" => array(
        "title" => "About The Bière Library"
    ),
    "events--dive-bar-night" => array(
        "title" => "Dive Bar Night at The Bière Library",
        "meta" => array(
            array("property" => "og:title", "content" => "Dive Bar Night at The Bière Library"),
            array("property" => "og:image", "content" => "https://$host/themes/biere-library/assets/images/apple-preview/home.png")
        )
    ),
    "careers" => array(
        "title" => "Jobs at the Bière Library",
        "meta" => array(
            array("property" => "og:title", "content" => "Jobs at the Bière Library"),
            array("property" => "og:image", "content" => "https://$host/themes/biere-library/assets/images/apple-preview/home.png")
        )
    )
);
