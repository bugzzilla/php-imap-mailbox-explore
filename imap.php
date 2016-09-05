<?php
if (count(($argv)) > 1) {
	unset ($argv[0]);
	$search = implode (' ',$argv);
} else { 
	$search = "ON \"".date ("d M Y")."\"";
}

$serv = '{'.gethostbyname('imap.yandex.ru').':993/novalidate-cert/ssl}';
echo $serv;
$mbox = imap_open($serv, 'admin@eyandex.ru', 'admin') or die(implode(', ', imap_errors()));
$folders = imap_list($mbox, $serv, '*') or die(implode(', ', imap_errors()));

echo "Mail search criteria: ". $search . "\n";
if (!is_array($folders)) {
	echo "Call failed\n";
} else {
    foreach ($folders as $val) {
	imap_reopen ($mbox, $val);
	$emails = imap_search($mbox, $search);
	if ($emails) {
	        echo "Mailbox: " . explode("}", mb_convert_encoding($val, "CP1251", "UTF7-IMAP"))[1] . "\n";
		foreach($emails as $mail) {
		        $header = imap_header($mbox, $mail);
			echo $header->Date . " " . $header->Subject . "\n";
		}
	}
    }
}
imap_close($mbox);
?>
