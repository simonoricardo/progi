# Stage 1: Build the app
FROM node:22-bullseye AS build

ENV PNPM_HOME="/pnpm"
ENV PATH="$PNPM_HOME:$PATH"

WORKDIR /app

# Install dependencies
COPY package.json pnpm-lock.yaml ./
RUN npm install -g pnpm; \
    pnpm setup; \
    export PNPM_HOME="/usr/local/share/pnpm" &&\
     export PATH="$PNPM_HOME:$PATH"; \
    pnpm install --frozen-lockfile

# Copy the rest of the app's source code
COPY . .

# Build the app for production
RUN pnpm build

# Stage 2: Serve the app
FROM nginx:alpine

# Copy the build output from the previous stage
COPY --from=build /app/dist /usr/share/nginx/html

# Expose port 80 for the web server
EXPOSE 80

# Start the Nginx web server
CMD ["nginx", "-g", "daemon off;"]

