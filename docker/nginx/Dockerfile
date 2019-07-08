FROM alpine:3.9

LABEL maintainer="Dimitar Nikov <dimiter.nikov@gmail.com>"

ENV TERM xterm
ENV container docker

RUN apk add --update nginx bash
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

ADD nginx.conf /etc/nginx/
ADD application.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server php:9001; }" > /etc/nginx/conf.d/upstream.conf

RUN adduser -S -D -H -G www-data www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
