<?php

header('Content-type: text/css');

$ef_options = EF_Event_Options::get_theme_options();

if (!empty($ef_options['ef_font'])) {
    $font = $ef_options['ef_font'];
    $font_css = "
                body {
                    font-family: '$font', sans-serif !important;
                }
                input, select, button, textarea {
                    font-family: '$font', sans-serif !important;
                }
                .site__title {
                    font-family: '$font', sans-serif !important;
                }
                .content h1,
                .content h2,
                .content h3,
                .content h4{
                    font-family: '$font', sans-serif !important;
                }
                .content .site__title {
                    font-family: '$font', sans-serif !important;
                }
                .menu__item {
                    font-family: '$font', sans-serif !important;
                }
                .menu__item a {
                    font-family: '$font', sans-serif !important;
                }
                .reg-btn {
                    font-family: '$font', sans-serif !important;
                }
                .news__session-item p{
                    font-family: '$font', sans-serif !important;
                }
                .schedule__info p{
                    font-family: '$font' !important;
                }
                .twittering__item p a {
                    font-family: '$font', sans-serif !important;
                }
                .action__subtitle {
                    font-family: '$font', sans-serif !important;
                }
                .action__content p{
                    font-family: '$font' !important;
                }
                .contact__subtitle{
                    font-family: '$font', sans-serif !important;
                }
                .fbook__subtitle {
                    font-family: '$font', sans-serif !important;
                }
                .video-card__place {
                    font-family: '$font', sans-serif !important;
                }
                .video-card p {
                    font-family: '$font', sans-serif !important;
                }
                .site__title > span {
                    font-family: '$font', sans-serif !important;
                }
                .content h1 > span {
                    font-family: '$font', sans-serif !important;
                }
                .content p {
                    font-family: '$font', sans-serif !important;
                }
                .content .site__title > span {
                    font-family: '$font', sans-serif !important;
                }
                .text-area > textarea {
                    font-family: '$font', sans-serif !important;
                }
                .text-field > input {
                    font-family: '$font', sans-serif !important;
                }
                .registration__title> span {
                    font-family: '$font', sans-serif !important;
                }
                .schedule__popup-place{
                    font-family: '$font', sans-serif !important;
                }
                .sponsors__subtitle {
                    font-family: '$font', sans-serif !important;
                }
                .sponsors__info p{
                    font-family: '$font', sans-serif !important;
                }
                .sponsors__face2 > p{
                    font-family: '$font' !important;
                }
                .tickets__subtitle {
                    font-family: '$font', sans-serif !important;
                    margin-bottom: 40px !important;
                }
                .tickets__item p{
                    font-family: '$font' !important;
                }
                .twittering__item p {
                    font-family: '$font' !important;
                }";
    echo $font_css;
}

if (!empty($ef_options['ef_primary_color'])) {
    $color = $ef_options['ef_primary_color'];
    $primary_color_css = "
                        a {
                            color: $color;
                        }
                        .site__header-btn {
                            border-bottom-color: $color;
                        }
                        .action__subtitle {
                            color: $color;
                        }
                        .contact__subtitle{
                            color: $color;
                        }
                        .fbook__subtitle {
                            color: $color;
                        }
                        .fbook__item > i{
                            color: $color;
                        }
                        .video-card p{
                            color: $color;
                        }
                        .video-card__place {
                            color: $color;
                        }
                        .inst__wrap h2 i {
                            color: $color;
                        }
                        .btn_3 {
                            background: $color;
                        }
                        .ribbon i.flaticon-vertical3 {
                            color: $color;
                        }
                        .dark .pagination-lnk {
                            color: $color;
                        }
                        .pagination li {
                            color: $color;
                        }
                        .pagination li a:hover{
                            color: $color;
                        }
                        .pagination li a.active {
                            color: $color;
                        }
                        .social a:hover {
                            color: $color;
                        }
                        .pagination-lnk,
                        .dark .pagination-lnk {
                            color: $color;
                        }
                        .news__item span{
                            color: $color;
                        }
                        .news__session-item header{
                            color: $color;
                        }
                        .registration__title> span {
                            color: $color;
                        }
                        .registration__subtitle {
                            color: $color;
                        }
                        .schedule__subtitle{
                            color: $color;
                        }
                        .schedule__card-title a:hover {
                            color: $color;
                        }
                        .schedule__face1 time{
                            color: $color;
                        }
                        .schedule__info {
                            background: $color;
                        }
                        .schedule__info a:hover{
                            color: $color;
                        }
                        .schedule__pagination-lnk:hover {
                            color: $color;
                        }
                        .schedule__pagination li {
                            color: $color;
                        }
                        .schedule__pagination li a:hover{
                            color: $color;
                        }
                        .schedule__pagination li a.active {
                            color: $color;
                        }
                        .schedule__popup-place{
                            color: $color;
                        }
                        .sponsors__subtitle {
                            color: $color;
                        }
                        .sponsors__face2{
                            background: $color;
                        }
                        .sponsors__face2 > a:hover {
                            color: $color;
                        }
                        .tickets__subtitle {
                            color: $color;
                        }
                        .tickets__price  .ribbon i.flaticon-vertical3 {
                            color: $color;
                        }
                        .tickets__buy{
                            background: $color;
                        }
                        .twittering__title i {
                            color: $color;
                        }
                        .twittering__item:nth-child(2n){
                            border-top: 3px solid $color;
                        }
                        .twittering__item p a {
                            color: $color;
                        }
                        .twittering__item > div > span > a {
                            color: $color;
                        }
                        .twittering__pagination-lnk {
                            color: $color;
                        }
                        .twittering__pagination li {
                            color: $color;
                        }
                        .twittering__pagination li a:hover{
                            color: $color;
                        }
                        .twittering__pagination li a.active {
                            color: $color;
                        }
                        .site__title > span{
                            color: $color;
                        }
                        .content h1 > span{
                            color: $color;
                        }
                        @media screen and (max-width: 1169px){
                            .twittering__item:nth-child(2n){
                                border-top-color: $color;
                            }
                        }";
    echo $primary_color_css;
}

if (!empty($ef_options['ef_secondary_color'])) {
    $color = $ef_options['ef_secondary_color'];
    $secondary_color_css = "
                            body {
                                background: $color;
                            }
                            .dark {
                                color: $color;
                            }
                            .dark .btn{
                                border-color: $color;
                                color: $color;
                            }
                            .dark .btn{
                                border-color: $color;
                                color: $color;
                            }
                            .dark .btn:hover {
                                background: $color;
                                border-color: $color;
                            }
                            .btn_3 {
                                color: $color;
                            }
                            .btn_3:hover {
                                color: $color;
                            }
                            .play {
                                border-color: $color;
                                color: $color;
                            }
                            .dark a {
                                color: $color;
                            }
                            .tags > i {
                                color: $color;
                            }
                            .menu__item a {
                                color: $color;
                            }
                            .reg-btn {
                                color: $color;
                            }
                            .reg-btn:hover {
                                color: $color;
                            }
                            .moreinfo:hover {
                                background: $color;
                            }
                            .dark .text-area > i {
                                color: $color;
                            }
                            .dark .text-area > textarea {
                                color: $color;
                            }
                            .star .fa-star-o {
                                color: $color;
                            }
                            .dark .pagination-lnk:hover {
                                color: $color;
                            }
                            .dark .pagination li a:hover{
                                color: $color;
                            }
                            .dark .text-field > i {
                                color: $color;
                            }
                            .dark .text-field > input {
                                color: $color;
                            }
                            .dark .social a {
                                color: $color;

                            }
                            .ares-select__popup ul {
                                background: $color;
                            }
                            .action {
                                background-color: $color;
                            }
                            .action.dark .site__title span {
                                color: $color;
                            }
                            .action__video {
                                color: $color;
                            }
                            .contact {
                                color: $color;
                            }
                            .contact__feedback {
                                border-bottom-color: $color;
                            }
                            .fbook__item {
                                background: $color;
                            }
                            .fbook__item > div {
                                color: $color;
                            }
                            .fbook__lnk a:hover {
                                color: $color;
                            }
                            .gallery-slider__points li i {
                                color: $color;
                            }
                            .gallery-slider__btn {
                                color: $color;
                            }
                            .gallery-point__btn {
                                color: $color;
                            }
                            .gallery__video {
                                color: $color;
                            }
                            .swiper-button{
                                color: $color;
                            }
                            .gallery-thumbs .swiper-slide i {
                                color: $color;
                            }
                            .index .swiper-button {
                                color: $color;
                            }
                            .index .swiper-pagination-bullet {
                                border-color: $color;
                            }
                            .swiper-pagination-bullet-active {
                                background: $color !important;
                            }
                            .video-card__reg {
                                color: $color;
                                border-color: $color;
                            }
                            .video-card__reg:hover {
                                background: $color;
                            }
                            .slider__points > li {
                                border-color: $color;
                            }
                            .slider__points > li.active {
                                background: $color;
                            }
                            .slider__btn {
                                color: $color;
                            }
                            .slider__close {
                                color: $color !important;
                                text-shadow: 0 0px 0 $color !important;
                            }
                            .news__popup {
                                color: $color;
                            }
                            .news__popup-close {
                                color: $color;
                            }
                            .news__popup-close:hover {
                                color: $color;
                            }
                            .news__author i,
                            .news__comments i {
                                color: $color;
                            }
                            .comments__text {
                                border-bottom-color: $color;
                            }
                            .comments__text cite {
                                color: $color;
                            }
                            .schedule__card-title {
                                color: $color;
                            }
                            .schedule__card-title a {
                                color: $color;
                            }
                            .schedule__info a:hover{
                                background: $color;
                            }
                            .schedule__popup {
                                color: $color;
                            }
                            .schedule__popup-close {
                                color: $color;
                            }
                            .schedule__popup-close:hover {
                                color: $color;
                            }
                            .schedule__pagination {
                                background: $color;
                            }
                            .schedule__pagination-lnk {
                                color: $color;
                            }
                            .schedule__tags {
                                color: $color;
                            }
                            .sponsors___content-title {
                                color: $color;
                            }
                            .sponsors__info p{
                                color: $color;
                            }
                            .sponsors__face2 > a:hover {
                                background: $color;
                            }
                            .tickets__item{
                                background: $color;
                            }
                            .tickets__item > .vertical-center {
                                background: $color;
                            }
                            .tickets__price{
                                color: $color;
                            }
                            .tickets__buy:hover {
                                color: $color;
                            }
                            .twittering__pagination {
                                background: $color;
                            }
                            .contact__feedback-submit button{
                                border-color: $color;
                                color: $color;
                            }
                            .contact__feedback-submit button{
                                border-color: $color;
                                color: $color;
                            }
                            .contact__subscribe input[type=email]{
                                border-color: $color;
                                color: $color;
                            }
                            .contact__subscribe button{
                                border-color: $color;
                                color: $color;
                            }
                            .video-card__title > span{
                                color: $color;
                            }
                            .video-card__lnk {
                                border-color: $color;
                            }
                            .video-card__lnk i {
                                color: $color;
                            }
                            .site__header:before {
                                border-color: $color transparent transparent transparent;
                            }
                            .spacer {
                                border-top-color: $color;
                            }
                            .countdown .countdown-section {
                                color: $color;
                            }
                            .site_opened .reg-btn {
                                border-color: $color;
                            }
                            .moreinfo {
                                color: $color;
                                border-color: $color;
                            }
                            .news__item {
                                color: $color !important;
                            }
                            .news__item-btn{
                                border-color: $color;
                                color: $color;
                            }
                            .news__session-item{
                                border-top-color: $color;
                                border-bottom-color: $color;
                            }
                            .vertical-center > div > div {
                                color: $color;
                            }
                            .schedule__info p{
                                color: $color !important;
                            }
                            .schedule__info a{
                                color: $color;
                                border-color: $color;
                            }
                            .schedule__popup-title{
                                color: $color;
                            }
                            .schedule__tags a{
                                color: $color;
                            }
                            .sponsors__face2 > h4{
                                color: $color;
                            }
                            .sponsors__face2 > p{
                                color: $color;
                            }
                            .sponsors__face2 > a{
                                border-color: $color;
                                color: $color;
                            }
                            .tickets__buy{
                                color: $color;
                            }
                            .twittering__item {
                                background: $color;
                            }

                            @media screen and (max-width: 767px){
                                .video-card p {
                                    color: $color;
                                }
                            }
                            @media screen and (max-width: 1169px){
                                .twittering__item {
                                    background: $color;
                                }
                            }";
    echo $secondary_color_css;
}

if (!empty($ef_options['ef_tertiary_color'])) {
    $color = $ef_options['ef_tertiary_color'];
    $tertiary_color_css = "
                            .action__title {
                                color: $color;
                            }
                            .action__content p{
                                color: $color;
                            }
                            .fbook__title {
                                color: $color;
                            }
                            .fbook__item > span{
                                color: $color;
                            }
                            .fbook__lnk a {
                                color: $color;
                            }
                            .gallery-slider__points {
                                border-bottom-color: $color;
                                border-top-color: $color;
                            }
                            .gallery-slider__points li {
                                border-left-color: $color;
                            }
                            .inst__wrap h2 {
                                color: $color;
                            }
                            .inst__gallery img {
                                border-left-color: $color;
                                border-top-color: $color;
                            }
                            .light {
                                color: $color;
                            }
                            .btn {
                                color: $color;
                            }
                            .dark .btn:hover {
                                color: $color;
                            }
                            .btn:hover {
                                border-color: $color;
                            }
                            .moreinfo:hover {
                                color: $color;
                            }
                            .text-area > i {
                                color: $color;
                            }
                            .text-area > textarea {
                                color: $color;
                            }
                            .text-area_filled > textarea,
                            .text-area > textarea:focus {
                                border-color: $color;
                            }
                            .pagination-lnk:hover {
                                color: $color;
                            }
                            .pagination li:before {
                                color: $color;
                            }
                            .pagination li a {
                                color: $color;
                            }
                            .text-field > i {
                                color: $color;
                            }
                            .text-field > input {
                                color: $color;
                            }
                            .text-field_filled > input,
                            .text-field > input:focus {
                                border-color: $color;
                            }
                            .copyright {
                                color: $color;
                            }
                            .social a {
                                color: $color;
                            }
                            .ares-select{
                                color: $color;
                            }
                            .ares-select__popup li:hover {
                                color: $color;
                            }
                            .ares-select__popup li.active {
                                color: $color;
                            }
                            .news__item {
                                border-top-color: $color;
                                border-left-color: $color;
                            }
                            .registration__title {
                                color: $color;
                            }
                            .samplepage .site__title{
                                color:$color;
                            }
                            .samplepage .content h1{
                                color: $color;
                            }
                            .samplepage .content h2{
                                color: $color;
                            }
                            .samplepage .content h3{
                                color: $color;
                            }
                            .samplepage .content h4{
                                color: $color;
                            }
                            .samplepage .content h5{
                                color: $color;
                            }
                            .samplepage .content h6{
                                color: $color;
                            }
                            .schedule__title{
                                color: $color;
                            }
                            .schedule__face1 {
                                border-top-color: $color;
                                border-left-color: $color;
                            }
                            .schedule__pagination li:before {
                                color: $color;
                            }
                            .schedule__pagination li a {
                                color: $color;
                            }
                            .sponsors__title {
                                color: $color;
                            }
                            .sponsors__item {
                                border-top-color: $color;
                            }
                            .sponsors__content_silver .sponsors__item {
                                border-left-color: $color;
                            }
                            .sponsors__content_bronze .sponsors__item{
                                border-left-color: $color;
                            }
                            .tickets__title {
                                color: $color;
                            }
                            .tickets__item{
                                border-top-color: $color;
                            }
                            .tickets__item p{
                                color: $color;
                            }
                            .tickets__item > .vertical-center {
                                color: $color;
                            }
                            .twittering__title {
                                color: $color;
                            }
                            .twittering__item {
                                border-top-color: $color;
                            }
                            .twittering__item > div > span {
                                color: $color;
                            }
                            .twittering__pagination-lnk:hover {
                                color: $color;
                            }
                            .twittering__pagination li:before {
                                color: $color;
                            }
                            .twittering__pagination li a {
                                color: $color;
                            }

                            @media screen and (max-width: 767px){
                                .inst__content img {
                                    border-left-color: $color;
                                    border-top-color: $color;
                                }
                            }
                            @media screen and (max-width: 1169px){
                                .twittering__item {
                                    border-top-color: $color;
                                }
                            }";
    echo $tertiary_color_css;
}

if (!empty($ef_options['ef_primary_background_color'])) {
    $color = $ef_options['ef_primary_background_color'];
    $primary_background_color_css = "
                                    .fbook {
                                        background: $color;
                                    }
                                    .inst {
                                        background: $color;
                                    }
                                    .page__scroll {
                                        background: $color;
                                    }
                                    .page__scroll > div:first-child {
                                        background: $color;
                                    }
                                    .light {
                                        background: $color;
                                    }
                                    .ares-select__popup li:hover {
                                        background: $color;
                                    }
                                    .ares-select__popup li.active {
                                        background: $color;
                                    }
                                    .news {
                                        background: $color;
                                    }
                                    .news__gallery:after {
                                        background: $color;
                                    }
                                    .registration {
                                        background: $color;
                                    }
                                    .schedule > header {
                                        background: $color;
                                    }
                                    .schedule__content:after {
                                        background: $color;
                                    }
                                    .sponsors {
                                        background: $color;
                                    }
                                    .tickets {
                                        background: $color;
                                    }
                                    .tickets__item{
                                        border-bottom-color: $color;
                                    }
                                    .twittering {
                                        background: $color;
                                    }
                                    .twittering__item:nth-child(2n){
                                        background: $color;
                                    }

                                    @media screen and (max-width: 1169px){
                                        .twittering__item:nth-child(2n){
                                            background: $color;
                                        }
                                    }";
    echo $primary_background_color_css;
}

if (!empty($ef_options['ef_secondary_background_color'])) {
    $color = $ef_options['ef_secondary_background_color'];
    $secondary_background_color_css = "
                                        .contact {
                                            background: $color;
                                        }
                                        .fbook__item > div {
                                            background: $color;
                                        }
                                        .dark {
                                            background: $color;
                                        }
                                        .btn_3:hover {
                                            background: $color;
                                        }
                                        .spacer i {
                                            background: $color;
                                        }
                                        .schedule__card-title {
                                            background: $color;
                                        }
                                        .schedule__popup {
                                            background: $color;
                                        }
                                        .schedule__popup .schedule__pagination{
                                            background: $color;
                                        }
                                        .sponsors___content-title {
                                            background: $color;
                                        }
                                        .tickets__price{
                                            background: $color;
                                        }";
    echo $secondary_background_color_css;
}

if (!empty($ef_options['ef_menu_background_color'])) {
    $color = $ef_options['ef_menu_background_color'];
    $menu_background_color_css = "
                                    .site__header{
                                        background: $color;
                                    }
                                    .site__header-btn{
                                        border-bottom-color: $color;
                                    }
                                    .menu{
                                        border-top-color: $color;
                                    }
                                    .site__header:after{
                                        background-color: $color;
                                        background: $color;
                                    }
                                        ";
    echo $menu_background_color_css;
}

if (!empty($ef_options['ef_menu_font_color'])) {
    $color = $ef_options['ef_menu_font_color'];
    $menu_font_color_css = "
                            .menu__item{
                                color: $color;
                            }
                            .site__header-btn{
                                color: $color;
                            }";
    echo $menu_font_color_css;
}

if (!empty($ef_options['ef_menu_item_background_hover_color'])) {
    $color = $ef_options['ef_menu_item_background_hover_color'];
    $menu_item_background_hover_color_css = "
                                        .menu__item:hover, .menu__item_opened, .menu__item.active{
                                            background: $color;
                                        }
                                        ";
    echo $menu_item_background_hover_color_css;
}

if (!empty($ef_options['ef_menu_item_font_hover_color'])) {
    $color = $ef_options['ef_menu_item_font_hover_color'];
    $menu_item_font_hover_color_css = "
                                        .menu__item:hover, .menu__item_opened, .menu__item.active{
                                            color: $color;
                                        }
                                        ";
    echo $menu_item_font_hover_color_css;
}

if (!empty($ef_options['ef_menu_item_font_size'])) {
    $size = $ef_options['ef_menu_item_font_size'];
    $menu_item_font_size = "
                                        .menu__item{
                                            font-size: $size;
                                        }
                                        ";
    echo $menu_item_font_size;
}