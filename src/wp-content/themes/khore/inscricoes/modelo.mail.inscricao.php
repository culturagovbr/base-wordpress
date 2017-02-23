<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
      <title><?php _e('Application completed!'); ?></title>
<style>
body {
    background-color: #000;
    color: #555;
    text-align: center;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 14px;
    line-height: 1.5em;
    text-align: justify;
}

a {
    color: #f00;
    text-decoration: none;
    font-weight: bold;
}

#content {
    width: 592px;
    margin: 0 auto;      
    background-color: #fff;
    margin-top: 20px;
 }

.header {
    background: #fff url('http://emergencias.cultura.gov.br/wp-content/themes/khore/images/bg-header-email.png') no-repeat;
    width: 100%;
    height: 123px;
 }

.content-inner {
    padding: 30px;
 }

h2 {
    font-family: Verdana,Geneva,sans-serif;
    font-size: 23px;
    font-weight: bold;
    color: #000;
    text-align: center;
 }

.message-social-network {
    font-weight: bold;
    text-align: center;
 }

.footer {
    background: #fff url('http://emergencias.cultura.gov.br/wp-content/themes/khore/images/logos-email.png') no-repeat;
    width: 530px;
    height: 59px;
    margin: 0 auto;
    padding-bottom: 60px;
 }

</style>
</head>
<body>
      <div id="content">
          <div class="header">&nbsp;</div>
          <div class="content-inner">
              <h2><?php _e('Application completed!', 'khore-child'); ?></h2>
              <p><?php _e('Hello, your application to participate of the debates, workshops, meetings, courses and the cultural programe of <a href="http://emergencias.cultura.gov.br">Emergências</a> was accepted.', 'khore-child'); ?></p>
              <p><?php _e('We will met from December 7 to 13 at Rio de Janeiro.', 'khore-child'); ?></p>
              <p class="message-social-network"><?php _e('Stay tuned on our social media and follow the news.', 'khore-child'); ?></p>
              <p class="message-social-network">
                  <a href="http://emergencias.cultura.gov.br"><?php _e('Site', 'khore-child'); ?></a> |
                  <a href="http://fb.com/emergencias.cultura">Facebook</a> |
                  <a href="https://www.facebook.com/events/1642806509329817"><?php _e('Event'); ?></a> |
                  <a href="http://twitter.com/emergencias2015">Twitter</a> |     
                  <a href="http://instagram.com/emergencias2015">Instagram</a>
              </p>
          </div>
          <div class="footer">
          </div>      
      </div>
</body>
</html>
