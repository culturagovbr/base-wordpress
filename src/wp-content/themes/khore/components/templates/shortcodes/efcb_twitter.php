<!-- twittering -->
<section class="twittering" data-section="twittering" <?php echo $args['styles']['section']; ?>>
    <!-- twittering__wrap -->
    <div class="twittering__wrap">
        <div class="container">
            <header class="row">
                <!-- twittering__title -->
                <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
                    <i class="fa fa-twitter" <?php echo $args['styles']['icon']; ?>></i>
                    <?php echo stripslashes($args['title']); ?>
                    <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
                </h2>
                <!-- /twittering__title -->
            </header>
            <!-- twittering__content -->
            <div class="twittering__content row" id="twitter_update_list">
                <?php
                if (!empty($args['tweets']) && property_exists($args['tweets'], 'statuses') && count($args['tweets']->statuses) > 0) {
                    for ($i = 0; $i < count($args['tweets']->statuses); $i++) {
                        ?>
                        <div class="twittering__item col-md-4 col-xs-12">
                            <div <?php echo $args['styles']['tweet']; ?>>
                                <p <?php echo $args['styles']['tweet_text']; ?>><?php echo khore_parse_tweet_text($args['tweets']->statuses[$i]->text); ?></p>
                                <a href="http://twitter.com/<?php echo $args['tweets']->statuses[$i]->user->screen_name; ?>" <?php echo $args['styles']['tweet_link']; ?>>
                                    <img src="<?php echo $args['tweets']->statuses[$i]->user->profile_image_url; ?>"
                                         alt="<?php echo $args['tweets']->statuses[$i]->user->name; ?>" width="50"
                                         height="50">
                                </a>
                                <span>
                                    <?php echo getRelativeTime($args['tweets']->statuses[$i]->created_at); ?>
                                    <a href="http://twitter.com/<?php echo $args['tweets']->statuses[$i]->user->screen_name; ?>"target="_blank" <?php echo $args['styles']['tweet_link']; ?>>
                                        @<?php echo $args['tweets']->statuses[$i]->user->screen_name; ?>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <!-- /twittering__content -->
        </div>
    </div>
    <!-- /twittering__wrap -->
</section>
<!-- /twittering -->