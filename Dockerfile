FROM debian:buster
# Todo actualizado
RUN apt -y update; apt -y upgrade
RUN apt -y install php7.3 apache2 vim locales
# Locales para es_ES
RUN sed -i '/es_ES.UTF-8/s/^# //g' /etc/locale.gen && \
    locale-gen
ENV LANG es_ES.UTF-8  
ENV LANGUAGE es_ES:es  
ENV LC_ALL es_ES.UTF-8  
# Web con micro CMS-LuisGulo
WORKDIR /var/www/html
RUN mv index.html debian.html
COPY src/ /var/www/html/
# Lanzar apache
CMD ["/usr/sbin/apachectl","-DFOREGROUND"]
