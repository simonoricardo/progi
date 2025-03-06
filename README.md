# Progi take-home assignment

## Details
This is a VueJS SPA backed with a Symfony API. The API uses Symfony 7.2 and PHP 8.4. The SPA uses Vue 3, Node 22 LTS, Typescript and Tailwind.

These details should have been provided to you via email but just in case:
- While modeling the backend, I did not use a `Vehicle` class although this would have made sense, especially while keeping the open-closed principle in mind. On the other hand, I also did not want to overdo it for a simple assignment, and this could as well have fallen into the YAGNI category. 
- I'm not sure if the code is organized the "Symfony" way and if I did or didn't do some things that are not "ok" within the community. I tried to read through the docs as much as I could, but that is also quite the task. 

## How to run

To run the project, there are 2 options. 

1. By using Docker (it works on my machine!):
- While being at the root of the repo (where this file is), run `docker-compose up --build` (add `-d` to run in detached mode).
- The VueJS SPA will be available at `http://localhost:5173` and the API (to test with Postman or other similar tools) at `http://localhost:8000`.

2. The traditional way:
- Install the SPA dependencies by running `pnpm install` in the `app` folder. `npm` can also be used, although it won't use the current lock file and will generate an extra `package-lock.json` file. The project can then be started by running `pnpm run dev` (or `npm run dev`).
- Install the API dependencies by running `composer install` in the `api` folder. Then start the server by running `symfony server:start`.

In both cases, the VueJS SPA should be available at `http://localhost:5173` and the API (to test with Postman or other similar tools) at `http://localhost:8000`.

## To run the tests
- The API tests can be ran by running `php bin/phpunit` in the `api` folder.
- The SPA tests can be ran by running `pnpm run test` in the `app` folder.
