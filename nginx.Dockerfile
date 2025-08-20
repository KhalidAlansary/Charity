FROM docker.io/node AS assets
WORKDIR /app
COPY package.json package-lock.json .
RUN npm ci
COPY assets assets
COPY public public
RUN npm run build

FROM docker.io/nginx
COPY nginx.conf /etc/nginx/nginx.conf
COPY --from=assets /app/public /var/www/public
