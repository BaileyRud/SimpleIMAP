<?php
include_once "imap.inc.php";

$imap = new SimpleIMAP("mail.example.com", "max@example.com", "test1234", 993);
//$imap = new SimpleIMAP("mail.example.com", "max@example.com", "test1234", 993, false);  # ssl deactivated
//$imap = new SimpleIMAP("mail.example.com", "max@example.com", "test1234", 993, true, false);  # ssl activated, but no cert-validation (for self-signed certs)


# count messages
$count = $imap->countMessages();
echo "Messages: ".$count."\n";

# get or set imap-timeout
$timeout = $imap->getTimeout();
echo "Timeout: ".$timeout."\n";
$imap->setTimeout(45);

# get inbox
$inbox = $imap->getInbox();
print_r($inbox);
