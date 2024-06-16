# SecureProxy

SecureProxy is an easy-to-use DNS solution designed to help users access restricted websites securely and efficiently. With its whitelist capabilities and simple setup process, SecureProxy ensures secure and unrestricted browsing on any device. Ideal for entry-level users, this solution bypasses censorship and provides safe, open internet access.

> [!CAUTION]
>
> The responsibility for using this program lies with the user. In some countries, including Iran, using proxies to bypass internet censorship is illegal. Users must ensure their use of ScureProxy complies with all applicable laws.

## Table of Contents

- [Features](#features)
- [How It Works](#how-it-works)
- [Architecture](#architecture)
- [Prerequisites](#prerequisites)
- [Deployment](#deployment)
- [Creating a Fillament User](#creating-a-fillament-user)
- [Database Seeding](#database-seeding)
- [Usage](#usage)
- [License](#license)

## Features

- **Easy Setup:** Quick and simple configuration process.
- **Whitelist Capabilities:** Control access to specific websites.
- **Secure Browsing:** Ensures safe and private internet access.
- **Cross-Device Compatibility:** Works on any device with internet access.
- **Admin Panel:** Central management through a Laravel-based admin panel.

## How It Works

SecureProxy operates as a central API within a microservice framework. It interacts with both DNS servers and proxy servers to manage and route traffic. The Laravel application serves as the backbone, providing an admin panel for configuration and monitoring.

### Virtualized Workflow

1. **DNS Request Handling:**
    - A client requests the resolution of a domain name.
    - The DNS server sends the client's IP address and the requested domain to the SecureProxy API.
    - The SecureProxy API checks if the domain and client IP pass the configured filters:
        - **If the domain and client IP pass the filters:** The API responds with the IP address of the proxy server instead of the real IP.
        - **If the domain and client IP do not pass the filters:** The API responds with the real IP address of the domain.

2. **Proxy Request Handling:**
    - When the client receives the proxy server IP, it attempts to connect to the requested domain through the proxy server.
    - The proxy server sends the client's IP address and the requested domain to the SecureProxy API.
    - The SecureProxy API verifies the request:
        - **If the request passes verification:** The proxy server routes the traffic to the intended destination, ensuring secure and unrestricted access.
        - **If the request does not pass verification:** The proxy server denies the request.

This process ensures that only authorized traffic is routed through the proxy, providing a secure and controlled browsing experience.

## Architecture

SecureProxy is built on a microservice architecture, where different components work independently yet cohesively:

- **Laravel Application:** Acts as the central API and admin panel.
- [**DNS Server:**](https://github.com/a1383n/dns_reverse_proxy) Interacts with the Laravel API to resolve domain names.
- [**Proxy Server:**](https://github.com/a1383n/secureforward-proxy) Routes traffic through the SecureProxy API for unrestricted access.

This modular approach allows for scalability, flexibility, and ease of maintenance.

## Prerequisites

- Docker
- Docker Compose
- Git

## Deployment

To deploy SecureProxy using Docker Compose, follow these steps:

1. **Clone the Repository:**
    ```bash
    git clone https://github.com/a1383n/secure-proxy.git
    cd secure-proxy
    ```

2. **Configure Environment Variables:**
    Create a `.env` file in the project root and configure your environment variables as needed. You can use the provided `.env.example` as a template.

3. **Build and Start the Containers:**
    ```bash
    docker-compose up -d
    ```

## Creating a Fillament User

To create a Fillament user, follow these steps:

1. **Enter the Application Container:**
    ```bash
    docker-compose exec app bash
    ```

2. **Run the Fillament User Creation Command:**
    ```bash
    php artisan make:fillament-user
    ```

3. **Follow the Prompts:** Provide the necessary information as prompted.

## Database Seeding

To seed the database with initial data:

1. **Enter the Application Container:**
    ```bash
    docker-compose exec app bash
    ```

2. **Run the Seeder:**
    ```bash
    php artisan db:seed
    ```

## Usage

Once deployed, see `http://127.0.0.1:8000`

## License

SecureProxy is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
