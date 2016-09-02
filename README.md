# bootish-server
Server side of the boot-blocker bootish

## Database
```
CREATE TABLE IF NOT EXISTS `sessions` (
  `iNoSession` int(11) NOT NULL AUTO_INCREMENT,
  `sID` text NOT NULL,
  `dDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bStatus` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iNoSession`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
```

See https://github.com/dfstorm/bootish for more information

