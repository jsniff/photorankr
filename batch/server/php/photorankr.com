<VirtualHost *:80>
  ServerAdmin matthew.sniff@photorankr.com
  ServerName photorankr.com
  ServerAlias photorankr.com www.photorankr.com
  ServerName photorankr.com
  ServerAlias photorankr.com www.photorankr.com
  # Indexes + Directory Root.
  DirectoryIndex index.html index.htm index.php
  DocumentRoot /home/photorankr/WWW/
  <Directory /home/photorankr/WWW/>
    Options -Indexes
  </Directory>
  # Logfiles
  ErrorLog /home/photorankr/logs/error.log
  CustomLog /home/photorankr/logs/access.log combined
</VirtualHost>
