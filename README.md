### RouteSMS PHP Library

A modular PHP library for interacting with RouteSMS.

######Installation
You will need composer in other to use this library. Head over to [Composer](https://getcomposer.org) to get started.

```
git clone git@github.com:cyberomin/RouteSMS-PHP.git

composer update
```

######Usage
```php
$sms = new RouteSMS($username, $password);

$result = $sms->send($sender, $recipient, $message, $type=0, $dlr=1);

echo $result; //success
```

If message is successful, you will get a `success` response else an exception will be thrown.

*$type:* Indicates the type of message.
Values for "type":-
0: Plain Text (GSM 3.38 Character encoding)
1: Flash Message (GSM 3.38 Character encoding)
2: Unicode
3: Reserved
4: WAP Push
5: Plain Text (ISO-8859-1 Character encoding)
6: Unicode Flash
7: Flash Message (ISO-8859-1 Character encoding)

*$dlr:* Indicates whether the client wants delivery report for this message
Range of values for "dlr":-
0: No Delivery report required
1: Delivery report required 

*$sender:* The source address that should appear in the message
Max Length of 18 if Only Numeric
Max Length of 11 if Alpha numeric
If you wish plus ('+') should be prefixed to the sender address when the message is displayed
 on the cell phone, please prefix the plus sign to your sender address while submitting the
message (note the plus sign should be URL encoded). Additional restrictions on this field may
be enforced by the SMSC. 

For more information please consult the [RouteSMS guide](http://routesms.com/downloads/resaller/RouteSms-Reseller-BulkApi.pdf)



