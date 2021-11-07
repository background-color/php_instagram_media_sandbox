<?php
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/class_db.php';
require_once dirname(__FILE__) . '/class_insta.php';

$insta = new InstagramMedia;
print $insta->get_media_url();
?>
