# SimpleIMAP v1.1
## PHP written IMAP-library for easy usage

SimpleIMAP is a very easy but functional IMAP client for PHP.  It contains the most functions needed to work with mailboxes.

### Features
- connects with every IMAP server (customized ports and SSL/TLS-settings like self-signed certificates are welcome)
- work's fine with bigger mailboxes, customizable timeout-value and caching
- based on the php-imap class
### In Planing (coming next time)
- flagging of mails (favorized mails, etc)
- StartTLS support for port 143

### Example

Loop all mails from inbox or get a single mail.
```php
<?php
include_once "imap.inc.php";

$imap = new SimpleIMAP("mail.example.com", "max@example.com", "test1234");
$msg_count = $imap->countMessages();

// loop inbox
$inbox = $imap->getInbox();
foreach($inbox as $mail){
    // show e-mail
    print_r($mail);
    
    // move to different folder
    $imap->move($mail['index'], "Drafts");
}

// get a single mail by index
$imap->get(5);

$imap->disconnect();
?>
```
