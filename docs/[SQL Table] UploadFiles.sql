CREATE TABLE `UploadFiles` (
  `FileID` varchar(40) DEFAULT '',
  `FileName` varchar(500) DEFAULT '',
  `FileSize` varchar(20) DEFAULT '',
  `FileHash` varchar(400) DEFAULT '',
  `DownloadCount` varchar(20) DEFAULT '',
  `UploadDate` varchar(20) DEFAULT '',
  `FileDownloadLimit` varchar(20) DEFAULT '',
  `FileValidDateLimit` varchar(20) DEFAULT '',
  `EndtoEndEncrypted` varchar(5) DEFAULT '',
  `BlockVPN` varchar(5) DEFAULT '',
  `DeletePassword` varchar(8) DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;