FROM wordpress

RUN echo "\nListen 8080\n" >> /etc/apache2/ports.conf
RUN echo "\n<VirtualHost *:*>\n</VirtualHost>\n" >> /etc/apache2/sites-available/000-default.conf
RUN cat /etc/apache2/ports.conf
RUN cat /etc/apache2/sites-available/000-default.conf
