FROM docker.io/node AS assets
RUN npm install -g pnpm
WORKDIR /app
COPY package.json pnpm-lock.yaml pnpm-workspace.yaml .
RUN pnpm install --frozen-lockfile
COPY . .
RUN pnpm run build

FROM docker.io/nginx
COPY nginx.conf /etc/nginx/nginx.conf
COPY src /var/www/html
COPY --from=assets /app/public /var/www/public
