# Charity

![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Postgres](https://img.shields.io/badge/postgres-%23316192.svg?style=for-the-badge&logo=postgresql&logoColor=white)
![HTMX](https://img.shields.io/badge/%3C/%3E%20htmx-3D72D7?style=for-the-badge&logo=mysl&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine%20JS-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=black)
![daisyUI](https://img.shields.io/badge/daisyUI-1ad1a5?style=for-the-badge&logo=daisyui&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white)

## Screenshots

TODO

## Table of Contents

- [Screenshots](#screenshots)
- [How to run and develop](#how-to-run-and-develop)
  - [Install Docker](#install-docker)
    - [MacOs and Windows](#macos-and-windows)
    - [Arch and Derivatives](#arch-and-derivatives)
    - [Other Linux Distributions](#other-linux-distributions)
  - [Create a .env file](#create-a-env-file)
  - [Build and run the project](#build-and-run-the-project)
  - [Open the application](#open-the-application)

## How to run and develop

The project uses docker to package and run. Make sure to install docker and docker-compose.

### Install Docker

#### MacOs and Windows

[Get Docker Desktop](https://docs.docker.com/get-started/get-docker/)

#### Arch and Derivatives

```bash
sudo pacman -S docker docker-{buildx,compose}
sudo systemctl enable --now docker.socket
```

Read [the wiki](https://wiki.archlinux.org/title/Docker) if you want more info (e.g. to run docker without sudo).

#### Other Linux Distributions

Follow the [official guide](https://docs.docker.com/engine/install/) to install docker

### Create a `.env` file

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Edit the `.env` file to add a database password.

### Build and run the project

```bash
docker compose up -d
```

### Open the application

Open your browser and go to [http://localhost:8080](http://localhost:8080)
