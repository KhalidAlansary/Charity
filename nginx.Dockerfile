FROM docker.io/node AS assets
WORKDIR /app
COPY package.json package-lock.json .
RUN npm ci
COPY . .
RUN npm run build

FROM docker.io/nginx
COPY nginx.conf /etc/nginx/nginx.conf
COPY src /var/www/html
COPY --from=assets /app/public /var/www/public
